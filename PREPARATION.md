# Prepare the javascript system
npm install yarn

## install packages / create lock file for packages with yarn (if the are installed with npm for example)
yarn 

 
https://www.youtube.com/watch?list=RDEMcUg-1M9Ga-Tfgz4ufrVCYQ

https://www.youtube.com/watch?list=RDEMPm-Ac8HMH-IbHCuNau3rJw

https://www.youtube.com/watch?list=RDEIuV7qGXmpk

# prepare database
bin/console doctrine:database:create
bin/console doctrine:schema:create

# build frontend
npm run build

# validate codecov file:
curl --data-binary @codecov.yml https://codecov.io/validate
