import requests
from requests.auth import HTTPBasicAuth


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
    def write_entity(self, count):

        drupal_user = self.get_drupal_user()
        endpoint = 'http://ap.local/jsonapi/employee_overview'
        payload = {
            "data": {

                "type": "employee_overview--employee_overview",
                "attributes": {
                    "title": "test entity",
                    "description": "hello from python",
                    "field_username": drupal_user,
                    "field_repetition": count,
                }
            }
        }
        requests.post(endpoint, headers=self._headers, auth=(self._user, self._password), json=payload)
