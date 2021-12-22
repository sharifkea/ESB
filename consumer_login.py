import re
from bottle import run, get, view, post, request, response
import json
import jwt
import requests
import random

from requests.models import requote_uri


##############################


@get("/")
@view("public/index.html")
def do():
    return dict(company_name="Token stuff")


# Here we process the jwt-token


@post("/process-jwt-token")
def do():
    result = ""
    authnumber = random.randint(1000,9999)
    try:
        
        send_sms(authnumber)
        # Here we load the jwt-token from https://ecuaguia.com/nemid.php.
        token = json.load(request.body)["jwt"]
        try:
            # Here we decode the token.
            result = jwt.decode(
                token, "jwt-secret-key", algorithms=["HS256"])
        except Exception as jwt_error:
            send_sms(jwt_error)

        try:
            # Here we check if email exist.
            if(result["email"] != None):
                print("*********************great***********************")
        except Exception as emailException:
            send_sms("Email missing")

    except Exception as json_error:
        send_sms(json_error)

    
    # newresult = json.dumps(result)
    # print(result)
    print(type(result))
    result["authnumber"] = authnumber
    print(result)
    # newresult["authnumber"] = authnumber
    print(result)
    return json.dumps(result)


def send_sms(message):
    endpoint = "https://fatsms.com/send-sms"
    phone = "71800374"
    my_api_key = "50af23a4-4ed0-4fa0-a3de-448a961f602f"
    data_dict = {"to_phone": phone, "api_key": my_api_key, "message": message}
    requests.post(endpoint, data=data_dict)

    print(str(data_dict))


##############################
run(host="127.0.0.1", port=4444, debug=True, reloader=True, server="paste")