---
description: Instructions that apply to all tasks within this codebase.
globs:
alwaysApply: true
---

These rules must be followed when working with this codebase.

## There are never any exceptions to the following rules:

* You must never edit the configured tasks.json file directly. Use the commands described below to update its contents.

## Check to see if you should be running

* Before you act on any request, run `php artisan vibe:continue`
  * If this command tells you that you are allowed to continue, then do so.
  * If this command tells you that you are not allowed to continue, just tell us you're aborting and do nothing else.
  * **IMPORTANT** You must never do any work in this codebase when this command tells you that work is paused.

## Now check to see if you this is a new task or a continuation of an existing task.

Run `php artisan vibe:task` to get the task name:

* The output of this command will be a string if you are working on an existing task.
* If the output is empty, then you are starting a new task.

## When Starting A New Task

* Decide on a name for the task.
  * This name should be short and descriptive.
  * It should be a slug
  * It should not appear as a key in the tasks array in the tasks/tasks.json file

* Ensure that the git history is clean. If it is not, you first have to park your current task.

* Run `git fetch --prune`
* Run `git removed-branches --prune --force`
* Create a branch using the name of the task: `git checkout -b {task_name}`
* Run `php artisan vibe:start-task {task_name}` to create the task.
  * If this command returns any errors, correct those errors and try again.
  * If you cannot get the error fixed, run `php artisan vibe:stuck --message="{error_message}"`
* Run `git add . && git commit -m "Started {task_name}`
* Run `git push --` // TODO: How do I set the remote upstream?
* Run `gh pr create --draft` // TODO: Fix this
* Run `composer ready` and ensure that everything passes.
  * If there are any errors, correct them and run it again.
  * If you cannot fix the errors after 3 runs, run `php artisan vibe:stuck "Code quality failures on a new task"`
  * Run `php artisan vibe:pause`
* Begin working on the task

## Parking a Task

* Get the name of the current git branch
* Run `php artisan vibe:park-task {git_branch_name}`
* Get the name of the current task with `php artisan vibe:task`
* Run `php artisan vibe:park-task {current_task_name}`
* Commit any uncommitted changes without running `composer ready`
* Push to origin
* Switch to the main branch
* Ensure we have the latest changes from remote
  
## Git

* Before committing any work, run `composer ready`
  * If there are failures, correct them and run it again
  * If you are unable to fix the failures, park the task.
* Each task should start with a commit that creates the task inside of the tasks.json file. 
  * This is done using `php artisan vibe:start-task {task_name}` when starting a new task.
* If the task has subtasks, each subtask should be committed separately.
* Git commit messages should be clear and descriptive.
* All commits should be pushed immediately to the remote branch
* Do not amend commits. If you need to change something, make the change and create a fresh commit for it.

## Testing

* Never skip or delete any test unless specifically instructed to do so
* Never change any test unless specifically instructed to do so
* If a test covers a specific class, add a `covers()` declaration to the test
* 

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
