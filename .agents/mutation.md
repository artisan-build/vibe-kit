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

**Important -** Do not edit any of the existing tests. Create new tests to cover the mutations.

**Important -** Do not edit any of the covered code.

If you are unable to cover the mutation by adding a test, leave the item unchecked and put an explanation below it. Move on to the next item in the checklist.
