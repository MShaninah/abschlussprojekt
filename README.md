# Abschlussprojekt — Gesundheit Management

Final project for my apprenticeship (*Abschlussprojekt*), by Mohamad Shaninah.

The project counts fitness exercise repetitions from a webcam stream using pose
estimation, and stores the result for the logged-in employee in a Drupal 9
back office. It consists of two parts that talk to each other over HTTP:

1. **Python tracker** — OpenCV + MediaPipe read the webcam, estimate body
   landmarks, and count bicep curls and squats by measuring joint angles.
2. **Drupal 9 site** — a custom module (`employee_overview`) defines a content
   entity that holds one training record per session, exposed through JSON:API
   so the tracker can write into it.

```
   ┌──────────────────────┐                    ┌───────────────────────────────┐
   │  Python (webcam)     │                    │  Drupal 9 (http://ap.local)   │
   │                      │  POST /currentuser │                               │
   │  main.py             ├───────────────────►│  EmployeeOverviewController   │
   │   └─ track.py        │◄───────────────────┤   → State 'User' (set on      │
   │        (MediaPipe)   │   {"user_name":…}  │      hook_user_login)         │
   │   └─ drupal.py       │                    │                               │
   │      (requests)      │  POST /jsonapi/    │  employee_overview entity     │
   │                      ├───────────────────►│   title, field_username,      │
   │                      │  employee_overview │   repetition counts           │
   └──────────────────────┘                    └───────────────────────────────┘
```

## Requirements

| Part | Needs |
| --- | --- |
| Drupal | PHP suitable for Drupal 9.3+, Composer, MySQL/MariaDB, Drush 10 |
| Python | Python 3, `opencv-python`, `mediapipe`, `numpy`, `requests` |
| Hardware | A webcam |

Local development was done in a Laravel Homestead VM (Vagrant + VirtualBox)
with the site reachable at `http://ap.local`.

## Installation

```shell
# 1. PHP dependencies (Drupal core + contrib land in web/)
composer install

# 2. Install the site, then import the exported configuration
drush site:install standard
drush config:import          # reads from config/sync
drush cache:rebuild

# 3. Python dependencies for the tracker
pip install opencv-python mediapipe numpy requests
```

The Drupal document root is `web/`. Site settings live in
`web/sites/default/settings.php` (not committed).

### Running the tracker

```shell
cd web
python main.py
```

A window titled *Mediapipe Feed* opens and shows the detected skeleton, the
current elbow angle and the repetition counter. Press **`q`** — or close the
window — to end the session. Only at that point are the counted repetitions
sent to Drupal.

## How it works

### Repetition counting (`web/track.py`)

`VideoStream.track()` opens the webcam through OpenCV and pushes every frame
into MediaPipe's pose model (`min_detection_confidence` / `min_tracking_confidence`
of `0.9`). From the returned landmarks it takes both arms
(shoulder → elbow → wrist) and both legs (hip → knee → ankle).

`calculate_angle(a, b, c)` computes the angle at the middle joint with
`numpy.arctan2`, normalising anything above 180° to its inner angle.

Counting is a two-stage state machine per limb:

| Exercise | Landmarks | "down" | "up" → count |
| --- | --- | --- | --- |
| Bicep curl | shoulder, elbow, wrist | angle > 160° | angle < 30° |
| Squat | hip, knee, ankle | angle > 160° | angle < 40° |

Arm reps accumulate in `_armCounter`, leg reps in `_legCounter`.

### Identifying the employee (`web/drupal.py` + the module)

Drupal has no session shared with the Python process, so the current user is
handed over through Drupal's state system:

1. `employee_overview_user_login()` (in `employee_overview.module`) fires on
   `hook_user_login` and dispatches `EmployeeOverviewEvent`.
2. `EmployeeOverviewSubscriber::onUserLogin()` writes the account name into
   `\Drupal::state()` under the key `User`.
3. `EmployeeOverviewController::currentUser()` serves that name as JSON at
   `/currentuser` (basic auth, permission `access overview`).
4. `DrupalUser.get_drupal_user()` reads it and uses it as `field_username`.

So the workflow is: log into the Drupal site, then start the tracker.

### Writing the record

`DrupalUser.write_entity(arm_count, leg_count)` POSTs a JSON:API document to
`/jsonapi/employee_overview` creating one `employee_overview--employee_overview`
resource with the username and the two repetition counts.

## The `employee_overview` module

`web/modules/custom/employee_overview/`

```
employee_overview.info.yml            Module definition (core ^9 || ^10)
employee_overview.module              hook_theme(), hook_user_login()
employee_overview.install             Install/uninstall hooks
employee_overview.routing.yml         /admin/structure/employee-overview, /currentuser
employee_overview.services.yml        Registers the event subscriber
employee_overview.permissions.yml     Entity + endpoint permissions
employee_overview.links.*.yml         Menu, task and action links
employee_overview.libraries.yml       CSS/JS assets
config/optional/                      REST resource config for the entity
css/employee-overview.css             Styling for the overview listing
templates/employee-overview.html.twig Entity template
src/
  Entity/EmployeeOverview.php               The content entity
  Controller/EmployeeOverviewController.php /currentuser endpoint
  Event/EmployeeOverviewEvent.php           Login event object
  EventSubscriber/EmployeeOverviewSubscriber.php
  Form/EmployeeOverviewForm.php             Add/edit form
  Form/EmployeeOverviewSettingsForm.php     Settings form
  EmployeeOverviewListBuilder.php           Admin listing incl. total count
  EmployeeOverviewAccessControlHandler.php  Per-operation access
  EmployeeOverviewInterface.php
```

### Entity

`EmployeeOverview` is a revisionable content entity (`RevisionableContentEntityBase`)
with base table `employee_overview` and revision table `employee_overview_revision`.

Base fields: `title`, `status`, `description`, `uid` (author, defaults to the
current user via `preCreate()`), `created`, `changed`.
Configured fields: `field_username` (string) and `field_repetition` (integer).

Admin paths:

| Path | Purpose |
| --- | --- |
| `/admin/content/employee-overview` | Collection / list builder |
| `/admin/content/employee-overview/add` | Create |
| `/employee_overview/{id}` | Canonical view |
| `/admin/structure/employee-overview` | Settings (Field UI base route) |

### API surface

| Endpoint | Provided by | Auth |
| --- | --- | --- |
| `POST /currentuser` | module route → controller | basic auth |
| `/jsonapi/employee_overview` | `jsonapi_extras` resource config (page limit 50) | basic auth |
| `/overview_endpoint` | REST export display of the `employee_overview` view (JSON) | basic auth |
| `/employee-overview` | Page display of the same view (mini pager, 10 rows) | permissions |

## Site configuration

The full site configuration is exported to `config/sync` (`drush config:export` /
`drush config:import`).

- Site name: **Gesundheit Management**, default language **de**
- Profile: `standard`; front-end theme **Olivero**, admin theme **Seven**
- Notable enabled modules: `employee_overview`, `jsonapi`, `jsonapi_extras`,
  `jsonapi_defaults`, `rest`, `hal`, `basic_auth`, `serialization`, `views`,
  `field_ui`, `language`, `locale`

Log in at `/user`. The credentials used by the tracker are currently hardcoded
in `web/drupal.py` — see the notes below.

## Known issues / open points

Things I would clean up next, kept here so they are not forgotten:

- **Hardcoded credentials.** `web/drupal.py` carries the Drupal username and
  password as class attributes (and they were also printed in the old README).
  They belong in environment variables or a config file outside version control,
  and the account should be a dedicated API user rather than the admin.
- **Field names are out of sync.** `drupal.py` posts `field_squat_repetition`
  and `field_biceps_repetition`, but `config/sync` only contains
  `field_repetition`. The exported configuration needs to be regenerated after
  the squat exercise was added (`drush config:export`), and the JSON:API
  resource config does not list the custom fields yet either.
- **Python code sits inside the document root.** `main.py`, `track.py` and
  `drupal.py` live in `web/`, which is served by the web server. They should
  move to a sibling directory (e.g. `tracker/`) outside the docroot.
- **Generator leftovers.** `employee_overview.install` still defines the
  scaffolded `employee_overview_example` schema and a `hook_requirements()` that
  reports a random number. `employee_overview.libraries.yml` references
  `js/employee-overview.js` (missing) plus unused jquery-labelauty and Vue.js
  libraries.
- **`olivero_theme()` in the module file.** `employee_overview.module` declares a
  second `hook_theme()` implementation named after the Olivero theme; a hook in a
  module file must be prefixed with the module name to be picked up.
- **Permission names mismatch.** `EmployeeOverviewAccessControlHandler` checks
  `add employee overview` while `permissions.yml` defines
  `create employee overview`; likewise `access employee overview overview` is
  defined but only `access overview` is used by the route.
- **Debug CSS.** `css/employee-overview.css` still sets `body { background: red; }`.
- **Malformed response header.** The `Content-Type` header array in
  `EmployeeOverviewController::currentUser()` has mismatched quotes.
- **Broad exception handling in `track.py`.** The landmark block uses a bare
  `except: pass`, which also hides the fact that the `*_stage` variables are
  read before they are assigned on the first frames.
- **Single-user assumption.** The current user is stored in a single global
  Drupal state key, so concurrent sessions would overwrite each other.
- **Committed macOS artefacts.** `.DS_Store` / `._.DS_Store` files should be
  removed and gitignored.

## Links

- Drupal 9 documentation: https://www.drupal.org/docs
- JSON:API in Drupal: https://www.drupal.org/docs/core-modules-and-themes/core-modules/jsonapi-module
- MediaPipe Pose: https://google.github.io/mediapipe/solutions/pose.html
- OpenCV: https://docs.opencv.org/
- Laravel Homestead (local VM used for development): https://laravel.com/docs/6.x/homestead
