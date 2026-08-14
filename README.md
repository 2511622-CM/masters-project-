# Masters Project Repository - Created from June 2026 - August 2026

## Contents
This repository and associated files were created by Courtney Morrison to test and iterate on two YAML scripts for an automated pipeline script.
The repo is laid as follows:
* category.php: shows the filtered category from the homepage.
* components.php: stores the functions for a separation of concerns.
* home.php: the main home page.
* pixelpantry.css: the CSS to style the site.
* products.php: showing a particular product that the user clicks on.

Within /workflows:
phplinter.yml - checks the above sites for any php syntax errors. If it finds none, the pipeline.yml will deploy, if it finds errors the pipeline will skip.
pipeline.yml - this checks and iterates the tokens and secrets stored in the repo.

### Accessing the site
The site can be accessed here: https://mayar.abertay.ac.uk/~2511622/MastersProject/Website/home.php
