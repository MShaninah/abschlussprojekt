import requests


class DrupalUser:
    # API Access Data
    _user = 'mo'
    _password = 'bPK5hB7kukW2kPs'
    _headers = {
        'Accept': 'application/vnd.api+json',
        'Content-Type': 'application/vnd.api+json'
    }

    """
        call Drupal 9 API to get the logged in user.
    """

    def get_drupal_user(self):
        endpoint = 'http://ap.local/currentuser'
        request = requests.post(endpoint, headers=self._headers, auth=(self._user, self._password))
        return request.json()['user_name']

    """
    call Drupal 9 API to create new entity.
    """

    def write_entity(self, arm_count, leg_count):
        drupal_user = self.get_drupal_user()
        endpoint = 'http://ap.local/jsonapi/employee_overview'
        payload = {
            "data": {

                "type": "employee_overview--employee_overview",
                "attributes": {
                    "title": "test entity",
                    "field_username": drupal_user,
                    "field_squat_repetition": leg_count,
                    "field_biceps_repetition": arm_count,
                }
            }
        }
        request = requests.post(endpoint, headers=self._headers, auth=(self._user, self._password), json=payload)
        return request.status_code
