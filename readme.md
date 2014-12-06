# AdapterBoilerplate

Is the starting point for creating a **K**Link Adapter. Offers some basic functionality for interacting with the KLink Core API and exposes the main interfaces that represents the data needed for every operation. The use of interfaces has been preferred over classes given the fact that the implementation can be different if an ORM is used or the CMS has some particular requirements.


**Composer is used for autoload and bootstrap, so only one file in your project must be included**


before doing anything please launch

	composer update --prefer-dist

to resolve all the dependencies.

Than in your project insert the line

	require_once dirname(__DIR__).'/vendor/autoload.php';


All dependecies with composer are automatically loaded and basic klink configuration are setup.




## feature offered

the Adapter boilerplate offers a common way to interact with the Klink Core API. The more advanced features will be offered by platform dependant Adapters. You can see the boilerplate as the common part of all adapters.

Globally the Adapter module is in charge of two main functions:

- Providing access to the documents of the target CMS and selecting their visibility.
- Receiving search requests by the target CMS, invoking the proper search & retrieve function exposed by the Core module, receiving the results and presenting them. The basic API of the Adapter module is shown below. The Adapter module can access to the data types and primitives provide by the Core module.

This boilerplate offers only the needed class to interact with the core with related data structures.

More info on the core API can be found

[waiting for new documentation page]

[old docs:]

- https://gitlab.klink.dyndns.ws:3000/kcore/kcore/wikis/kcore-apis
- https://gitlab.klink.dyndns.ws:3000/klinkdocumentation/referencearchitecture/wikis/KLINK-Core-API

