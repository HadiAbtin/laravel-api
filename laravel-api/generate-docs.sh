#!/bin/bash

# Get APP_DOC_URL from environment variable or use default
APP_DOC_URL=${APP_DOC_URL:-"https://example.com"}

echo "Generating API documentation with URL: $APP_DOC_URL"

# Replace the placeholder in apiblueprint.apib
sed -i.bak "s/{{APP_DOC_URL}}/$APP_DOC_URL/g" docs/api/apiblueprint.apib

# Generate the documentation
apibmerge docs/api/blueprint docs/api/apiblueprint.apib
aglio --theme-variables flatly --theme-template triple -i docs/api/blueprint/apidocs.apib -o resources/views/apidocs.blade.php

# Restore the original file
mv docs/api/apiblueprint.apib.bak docs/api/apiblueprint.apib

echo "API documentation generated successfully!"
