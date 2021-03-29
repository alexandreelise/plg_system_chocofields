.PHONY: gen all test install help

.DEFAULT_GOAL= help

CURRENT_DIR=$$(pwd)
CURRENT_DATETIME=$$(date +%Y%m%d%H%M)
BUILD_DIR=$$(pwd)

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

gen: ./src ## Create extension zip file
	mkdir -p $(BUILD_DIR)/build \
	&& cd $(CURRENT_DIR)/src \
	&& find . -type f -name "*.php" -exec php -l "{}" \; \
	&& zip -9 -r $$(dirname $(BUILD_DIR))/build/$$(basename $$(dirname $(CURRENT_DIR)))_$(CURRENT_DATETIME).zip . \
	&& cd ..

composer.lock: composer.json      ## create composer.lock file if not exists
	php composer.phar update

vendor: composer.lock             ## Create vendor directory if not exists
	php composer.phar install

install: vendor                   ## Install extension dependencies using composer.phar

test: install ./vendor/bin/phpunit ./joomla3x ./src ./tests ./bootstrap.php ./phpunit.xml ## Extension unit tests using Joomla! 3
	./vendor/bin/phpunit --testdox --configuration ./phpunit.xml

all: test gen
