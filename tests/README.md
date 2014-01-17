How to run these tests
======================

 * Download and install phpunit (http://phpunit.de/manual/3.7/en/installation.html)
 * `phpunit tests/unit`
 
 
How to add tests
================

 * Add unit tests to the matching folder/file.php or create a new one
 * Add any new suites to the phpunit.xml file
 * Below is the suite entry for the sample test in unit/config/SampleTest.php
 
```
<testsuite name="SampleTest">
  <directory>unit/config</directory>
</testsuite>
```
