# doctrine2-custom-types

Custom data types for Doctrine2.

## ApproxDate
### A pseudo-date object to store incomplete date values.
Accepts dates in the following formats:
(lowercase does not require padding with 0)

- YY
- YYYY
- mm/YY, mm-YY, mm.YY
- mm/YYYY, mm-YYYY, mm.YYYY
- mm/dd/YYYY, mm-dd-YYYY, mm.dd.YYYY
- YYYYMMDD
- YYYY-MM
- YYYY-MM-DD

## Gender
### A person's gender.
