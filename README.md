# HexTemplate

Template for creating DDD, hexagonal, potentially framework-agnostic, persistent APIs.  

Realized with two test entities TestItem and TestCollection. Implemented with Symfony + ApiPlatform as Infrastructure layer.

Run **make help** to see basic actions.

### How to use

Clone the repository by running **git clone https://github.com/Aterlupus/hextemplate.git**

Enter project directory by running **cd hextemplate**

Run **make install** to up containers, install dependencies and create DB schemas.
If execution breaks on *An exception occurred in the driver: SQLSTATE[HY000] [2002] Connection refused* error, wait a moment and run **make install** again (this will be fixed in future releases).

Now you can:
 - run **make phpunit** to confirm that basic functionalities work 
 - run **make deptrac** to confirm separation between hexagonal layers
 - run **make php** to enter docker php container
 - run **make update-db** to update DB schema (uses **--force**, use wisely)
 - run **make stop** to stop containers
 - run **make down** to tear down containers
 - enter **http://localhost:8001/** to see api docs
 - enter **http://localhost:8012/index.php?db=newproject** with **admin/admin** credentials to explore database
 - import Postman workspace JSON to test API for yourself **https://pastebin.com/Mz6s1mH6**
 - add your own domains to `src` directory
 - generate a domain (described below)

### Domain Generation

#### After downloading dependencies change *./api/vendor/nette/php-generator/src/PhpGenerator/Printer.php* *printAttribute* method (line 397) accessor to *protected* for generator to work (this will be fixed in future releases).  

#### After failed app:generate:domain execution remove this domain's directory (if it was created)

Create a `[domain].yaml` file for path `src/Command/Generate/schema/[domain].yaml` that will contain description of domain entity and its properties. See `src/Command/Generate/schema/TestTag.yaml` for practical reference. File format is:

```
domain: [domain name]
properties:
  id:
    type: string
  [property name]:
    [property options]
```

**[domain name]** and **[property name]** can be alphanumerical strings

Available **[property options]** are:
 - type - available types are:
   - string
   - ?string
   - int
   - bool
   - array
 - minLength - minimum string length
 - maxLength - maximum string length
 - externalDomain - name of Entity if property is id of some Entity

Enter php container by running **make php**

Run **bin/console app:generate:domain [domain]**

Run **make update-db** to update DB schema

Enter **http://localhost:8001/** to see docs of newly generated entity.

### Example TestTag Domain

Run **bin/console app:generate:domain TestTag** to generate TestTag domain from file `TestTag.yaml` which is already present in the project.
