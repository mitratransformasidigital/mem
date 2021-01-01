# mem
Mitral Employee Management

###Deployment: 
https://mem.mitral.co.id/

### cPanel Git 
```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/mitral/mem
    - /bin/cp -R * $DEPLOYPATH
 ```
