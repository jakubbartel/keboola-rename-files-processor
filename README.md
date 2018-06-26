# Keboola Rename Files Processor

Rename files base on given regular expressions.

## Functionality

Processor takes all files in input directory `/data/in/files` and applies configured regular expressions to generate
new file name.

All files that does not match with the pattern's regular expression are left with actual name.

The pattern is applied to the filename (relative file path more precisely) after `/data/in/files`, e.g. file
`/data/in/files/report.csv` is renamed from `report.csv`, file `/data/in/files/dir/report.csv` is renamed
from `dir/report.csv`.

No files processing order is guaranteed and files with duplicated names are going to replace each other.

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

Renames all files in `/data/in/files` that have a number in file name ending, e.g. `/data/in/files/myreport-123.csv`
to `/data/out/files/eshops/123/myreport.csv`.
