Reverse Hash 

Assignment specification: https://www.wa4e.com/assn/crack/

This application uses a very simple brute force attack to 
"reverse" an MD5 hash.  It is really not reversing the hash
at all as that would be impossible.  Instead it knows that 
the original pre hash text was a lower case character string with 
exactly two characters.

So the application uses two nested loops and tests all 
26*26 combinations of two lower case letters, and computes the
hashes of those values and checks to see if the computed hash
matches.

Demo: http://sjzhao.byethost33.com/week3/crack/


