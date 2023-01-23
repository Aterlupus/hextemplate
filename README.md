# HexTemplate

Template for creating DDD, hexagonal, potentially framework-agnostic, persistent APIs.  

Realized with two test entities TestItem and TestCollection. Implemented with Symfony + ApiPlatform as Infrastructure layer.

Run **make help** to see basic actions

### How to use

Clone the repository by running **git clone https://github.com/Aterlupus/hextemplate.git**

Enter project directory by running **cd hextemplate** 

Run **make install** to up containers, install dependencies and create DB schemas.
If execution breaks on *An exception occurred in the driver: SQLSTATE[HY000] [2002] Connection refused* error, wait a moment and run **make install** again (this will be fixed in future releases)

Now you can:
 - run **make phpunit** to confirm that basic functionalities work 
 - run **make deptrac** to confirm separation between hexagonal layers
 - run **make php** to enter docker php container
 - run **make stop** to stop containers
 - run **make down** to tear down containers
 - enter **http://localhost:8001/** to see api docs
 - enter **http://localhost:8012/index.php?db=newproject** with **admin/admin** credentials to explore database
 - import Postman workspace JSON to test API for yourself **https://pastebin.com/Mz6s1mH6**
