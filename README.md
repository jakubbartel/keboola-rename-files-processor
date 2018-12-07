# Keboola Rename Files Processor

[![Build Status](https://travis-ci.org/jakubbartel/keboola-rename-files-processor.svg?branch=master)](https://travis-ci.org/jakubbartel/keboola-rename-files-processor)

Rename files based on the given regular expressions.

## Usage

The processor takes all files in the input directory `/data/in/files` and uses the configured regular expressions to generate
new file name.

All files that do not match with the regular expression pattern are moved to the output with their current name.

The pattern is applied to relative file path after `/data/in/files`. e.g.:
- for the file `/data/in/files/report.csv`, the pattern is applied to `report.csv`,
- for the file `/data/in/files/dir/report.csv`, the pattern is applied to `dir/report.csv`.

No file processing order is guaranteed and files with duplicated names are going to replace each other.

## Configuration

Example processor configuration:
```
{
    "definition": {
        "component": "jakub-bartel.processor-rename-files"
    },
    "parameters": {
        "pattern": "/^(.+)-(\d+)\.csv$/",
        "replacement": "/eshops/$2/$1.csv"
    }
}
```

The configuration renames all files in `/data/in/files` that have a file name ending with number to subdirectory with that number. e.g. file `/data/in/files/myreport-123.csv` will be renamed to `/data/out/files/eshops/123/myreport.csv`.
