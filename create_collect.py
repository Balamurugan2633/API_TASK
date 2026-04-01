import requests
import json
import time

access_key = 'ak_test_cM7M7FHfXQhABa8l8Ry20xayfnyUcO7zta4N'
api_secret = '948005f9fdc951237b752b18912e6a2eef875bf2'
account_id = 'va_VjZoGFRRfwp2tG5O4KqM7H2cT'

url = f"https://api.zwitch.io/v1/accounts/{account_id}/payments/upi/collect"
auth = f"Bearer {access_key}:{api_secret}"

headers = {
    "Authorization": auth,
    "Content-Type": "application/json",
    "Accept": "application/json"
}

payload = {
    "remitter_vpa_handle": "anil.reddy@okicici",
    "amount": 10,
    "expiry_in_minutes": 10,
    "remark": "Test UPI Collect link from Antigravity",
    "merchant_reference_id": f"TXN_{int(time.time())}"
}

print(f"Calling endpoint: {url}")
try:
    response = requests.post(url, headers=headers, json=payload)
    print(f"Status Code: {response.status_code}")
    print("Response Content:")
    print(json.dumps(response.json(), indent=2))
except Exception as e:
    print(f"Error: {e}")
