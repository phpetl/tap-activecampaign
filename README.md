# PHPETL - ActiveCampaign Tap

An implementation of a [Singer](https://www.singer.io/) data tap for ActiveCampaign.

## Installation

    composer require phpetl/tap-activecampaign

## Configuration

This target requires the use of a configuration file to talk to the MySQL database.

Copy the `config.json.dist` file to `config.json` and edit with the appropriate values for your database.

You will also need to create a catalog of all the tables to be extracted. Copy the `data/base_catalog.json.dist` to `data/base_catalog.json` to get started. 

See also the [JSON Schema specification](https://json-schema.org/understanding-json-schema/) for additional information on writing JSON Schemas, and the [Singer Catalog Format](https://github.com/singer-io/getting-started/blob/master/docs/SPEC.md#catalog) docs for information on how catalogs work in Singer.

## Usage

As with other Singer-compatible targets, this tap will export data from all known catalog options in a [Singer-compatible tap format](https://github.com/singer-io/getting-started/blob/master/docs/SPEC.md#output). This output can be piped into a file for later ingestion, or directly into a target.

    bin/tap-activecampaign --config /path/to/config.json > data.json
