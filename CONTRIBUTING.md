# How to contribute

Third-party patches are much appreciated. This is a rather large project
and a single person can not work on it 24/7 to address all issues and
feature requests. If you feel comfortable with PHP and Smarty you should
consider following this contribution guide!

## Getting Started

* Make sure you have a [GitHub account][4].
* Submit an [Issue][1] for your issue, assuming one does not already exist.
  * Clearly describe the issue including steps to reproduce when it is a bug.
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository into your GitHub account

## Making Changes

* Create a topic branch from where you want to base your work.
  * This is usually the `next` branch.
  * Only target release branches if you are certain your fix must be on that
    branch.
  * To quickly create a topic branch based on `next`; `git branch
    fix/next/my_contribution next` then checkout the new branch with `git
    checkout fix/next/my_contribution`.  Please avoid working directly on the
    `next` branch.
* Make commits of logical units.
* Check for unnecessary whitespace with `git diff --check` before committing.
* Make sure your commit messages are in the proper format.

````
    (#99999) Make the example in CONTRIBUTING imperative and concrete

    Without this patch applied the example commit message in the CONTRIBUTING
    document is not a concrete example.  This is a problem because the
    contributor is left to imagine what the commit message should look like
    based on a description rather than an example.  This patch fixes the
    problem by making the example concrete and imperative.

    The first line is a real life imperative statement with an issue number
    from our issue tracker.  The body describes the behavior without the patch,
    why this is a problem, and how the patch fixes the problem when applied.
````

## Submitting Changes

* Push your changes to a topic branch in your fork of the repository.
* Submit a pull request to the origin repository.
* Update your issue that you have submitted code and are ready for it to be reviewed.
  * Include a link to the pull request in the ticket

# Additional Resources

* [Issue Tracker][1]
* [General GitHub documentation][2]
* [GitHub pull request documentation][3]

[1]: https://github.com/TheSerapher/php-mpos/issues "Issue"
[2]: http://help.github.com/ "GitHub documentation"
[3]: http://help.github.com/send-pull-requests/ "GitHub pull request documentation"
[4]: https://github.com/signup/free "GitHub account"
