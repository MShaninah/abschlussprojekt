import requests
from requests.auth import HTTPBasicAuth
from pprint import pp

"""
call Drupal 9 API with POST method to create new content.
"""

endpoint = 'http://ap.local/jsonapi/employee_overview'

user = 'mo'
password = 'bPK5hB7kukW2kPs'

headers = {
    'Accept': 'application/vnd.api+json',
    'Content-Type': 'application/vnd.api+json'
}

payload = {
    "data": {
        "type": "employee_overview--employee_overview",
        "attributes": {
            "status": True,
            "title": "test entity",
            "description": "hello from python",
            "field_user_id": 1,
        }
    }
}

r = requests.post(endpoint, headers=headers, auth=(user, password), json=payload)
pp(r.headers)
pp(r.status_code)
pp(r.json())
