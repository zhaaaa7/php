## Notes:

1. Basic Rule: Don’t put the same string data in twice - use a relationship instead
2. Which table should we start with?
3.
* Do not replicate data. Instead, reference data. Point at data.
* Use integers for keys and for references.
* Add a special “key” column to each table, which you will make references to.


4. Naming rules: xx_id for primary or foreign key
                   index for Logical key

5. primary keys should be mad first, then be referred to as foreign keys by other table
6. join without “ON” produce all combination, row1*row2
7. combination/many-to-many table, don’t need to have a primary key, composed by foreign keys, the combination is unique
8. Integers are efficient
