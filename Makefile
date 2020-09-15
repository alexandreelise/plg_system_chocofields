.PHONY: gen doc all test3 test4 test install help

.DEFAULT_GOAL= help

CURRENT_DIR=$$(pwd)
CURRENT_DATETIME=$$(date +%Y%m%d%H%M)
BUILD_DIR=$$(pwd)
RELEASE_DIR='/home/alexandree/Common/Tools/buildserver/releases'
CURRENT_VERSION='2-0-0'

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

gen: ./src ## Create extension zip file
	mkdir -p $(BUILD_DIR)/build \
	&& cd $(CURRENT_DIR)/src \
	&& find . -type f -name "*.php" -exec php -l "{}" \; \
	&& zip -9 -r $$(dirname $(BUILD_DIR))/build/$$(basename $$(dirname $(CURRENT_DIR)))_$(CURRENT_DATETIME).zip . \
	&& cd ..

release: ./src ## Copy extension zip to release folder
	mkdir -p $(RELEASE_DIR) \
	&& cd $(CURRENT_DIR)/src \
	&& find . -type f -name "*.php" -exec php -l "{}" \; \
	&& zip -9 -r $(RELEASE_DIR)/$$(basename $$(dirname $(CURRENT_DIR)))-$(CURRENT_VERSION).zip . \
	&& cd ..

composer.lock: composer.json      ## create composer.lock file if not exists
	php composer.phar update

vendor: composer.lock             ## Create vendor directory if not exists
	php composer.phar install

install: vendor                   ## Install extension dependencies using composer.phar

test4: install ./vendor/bin/phpunit ./joomla4x ./src ./tests ./bootstrap-joomla4x.php ./phpunit-joomla4x.xml ## Extension unit tests using Joomla! 4
	./vendor/bin/phpunit --testdox --configuration ./phpunit-joomla4x.xml

test3: install ./vendor/bin/phpunit ./joomla3x ./src ./tests ./bootstrap-joomla3x.php ./phpunit-joomla3x.xml ## Extension unit tests using Joomla! 3
	./vendor/bin/phpunit --testdox --configuration ./phpunit-joomla3x.xml

test: test3 test4 ## Both extension unit tests using Joomla! 3 and Joomla! 4
	echo 'Unit tests using Joomla! 3 and Joomla! 4 done.'
doc: ./src ./docs ./tests ## Generate extension documentation
	$(CURRENT_DIR)/vendor/bin/phpdoc

all: test doc gen
