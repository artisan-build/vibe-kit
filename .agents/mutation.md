---
description: Instructions for mutation testing
globs:
alwaysApply: false
---

When you are asked to build up our mutation coverage, follow these instructions:

Run `herd coverage ./vendor/bin/pest --mutate > mutation.txt`

Read `mutation.txt` to get the output of the mutation test and create a checklist of untested mutations in `./.tasks/mutations.md`

Once all of the untested mutations have been copied into the checklist, delete the mutation.txt file.

Add tests to cover all untested mutations in the `./.tasks/mutations.md` file and re-run the test by id to verify that it is covered.

When you have finished all of the items in the checklist, re-start this workflow and run it again until there are no untested mutations.
