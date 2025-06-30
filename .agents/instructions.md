---
description: Instructions that apply to all tasks within this codebase.
globs:
alwaysApply: true
---

These rules must be followed when working with this codebase.

## Git

1. All new tasks must be started with a clean git state unless you are specifically instructed otherwise.
2. If there are any unstaged or uncommitted files, ask us what to do before continuing

## Testing

1. Before starting any task, run `php artisan test` and ensure that the tests are passing. If they are not, stop and ask us what to do.
2. Unless specifically instructed to do so, do not change, skip, or delete any test.
3. If completing a task as instructed requires a change to an existing test, ask us for confirmation before doing that work.
4. For tests that cover a specific class, add the `covers()` directive at the top of the test.

## When Completing Any Task
1. No matter the size of the task, the first step is to plan it out.
2. Create a checklist at /.tasks/{year}-{month}-{day}-{task_name}.md
3. When you have finished creating the checklist, evaluate each task to see if it should be broken into subtasks.
4. If there are no subtasks, then you should immediately proceed to do the work, marking off each item in the checklist when it is complete.
5. If there are subtasks, you should ask me to review the task list before you start work.
6. When you have completed the work, "sign" the task list by adding "Completed by {agent_name}" at the bottom of the list

## Do Not Introduce Unexpected External Dependencies

We maintain a list of approved third-party packages that may be used in this project at dependencies.md.
If the package is listed in that file and not present in the project, you may add it as a dependency.
If you see {vendor}/* in the dependencies.md file, it means that you may use any package from that vendor (eg artisan-build/* means you can pull in any package from Artisan Build)

If the package you want to use is not included in the file, you should stop and ask us if we want to use it before proceeding.

## When Fixing A Bug

1. Write a test that fails because the bug is present.
2. Fix the bug and ensure that the test is passing.
3. Leave the test in place to guard against regression.

## When Implementing a Feature

1. Plan the implementation.
2. Use test-driven development to create the feature. Start with writing a test that sets out the expectations and then build the implementation to make the test pass.
3. Add documentation of the new feature to the README.md file

## Finishing Up Any Project

1. Run `composer ready` to execute our automatic linting and code quality checks.
2. If any errors are present when running `composer ready`, fix them and continue running `composer ready` until it is error-free.
3. Write thorough commit messages to make it easy as possible for us to review your work.
