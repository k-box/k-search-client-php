# Adapter Boilerplate Contribution Guide

This document provides a set of best practices for bug reports, code submissions / pull requests, etc.

## Submitting bug reports

Bugs are tracked as [GitHub issues](https://github.com/k-box/k-search-client-php/issues).

To submit a bug report create an issue that explains the problem and include additional details to help maintainers reproduce the problem:

- Search the project’s issue tracker to make sure it’s not a known issue.
- Use a clear and descriptive title for the issue to identify the problem.
- Describe the exact steps which reproduce the problem in as many details as possible. For example, start by explaining what API you've used, what parameters,..
- Provide specific examples to demonstrate the steps. Include links to files or projects, or copy/pasteable snippets, which you use in those examples. If you're providing snippets in the issue, use Markdown code blocks.
- Describe the behavior you observed after following the steps and point out what exactly is the problem with that behavior.
- Explain which behavior you expected to see instead and why.

Please make sure to highlight also:

- the PHP version you are using
- What operating system are you on? Windows? (Vista? 7? 32-bit? 64-bit?) Mac OS X? (10.7.4? 10.9.0?) Linux? (Which distro? Which version of that distro? 32 or 64 bits?).


## Contributing changes

- Always make a new branch for your work, no matter how small. This makes it easy for others to take just that one set of changes from your repository, in case you have multiple unrelated changes floating around.

 - A corollary: don’t submit unrelated changes in the same branch/pull request! The maintainer shouldn’t have to reject your awesome bugfix because the feature you put in with it needs more review.

- Base your branch on the `master` branch
- Add unit tests


### General flow

1. Fork the project, creating e.g. `yourname/k-search-client-php`.
2. Clone the project on your local environment `git clone https://github.com/yourname/k-search-client-php.git`
3. Create the branch for the feature `git checkout -b [name_of_your_new_branch]`
4. Write tests expecting the correct/fixed functionality; make sure they fail.
5. Make your changes to the source code.
6. Run tests again, making sure they pass.
7. Commit your changes: `git commit -m "Closes #1 - Foo the bars"`. If you have created 2 or more commits please squash them in a single commit and always mention the reference issue.
8. Push your commit to get it back up to your fork: `git push`.
9. Create a Pull request and let it go.


