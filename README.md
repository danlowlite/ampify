# ampify
A Project to automatically make AMP (Accelerated Mobile Pages) in PHP. 

This currently represents a method to create them. It is not the most elegant solution, but it works for me and creates valid AMP-HTML based on my input files. Those files only have a small selection of markups. 

This is not fully functional yet. It does not properly parse all the required structured data needed by Google. 

# usage
0. Copy ampify.php to directory. Requires PHP 5.5+. 
1. Generate your file. We'll be using as an example the "stub.php" file. 
2. Include relevant PHP stuffs in your header element. 
3. Make a copy of the "amp_stub.php" and substitute "stub" with the name of your file.
4. Go. 

# todo

More robust parsing of structured data things that Google needs. 
