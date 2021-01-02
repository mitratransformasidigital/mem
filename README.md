# mem
Mitral Employee Management

### Deployment
1. clone this github repository into your local PC.
2. do changes on your local pc.
3. open a Command Line Interface on the directory and do following git commands
•	To add your changes ``` git add ```
•	To add a message and commit ```git commit -m “your message here” ```
•	To push changes into GitHub ``` git push ```
4. Open cPanel and pull your changes using Git plugin.

Your changes should be visible here: https://mem.mitral.co.id/


### cPanel Git 
```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/mitral/mem
    - /bin/cp -R * $DEPLOYPATH
 ```
