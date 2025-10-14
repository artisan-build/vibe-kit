# Implement Single Task (Single-Agent Mode)

This command implements ONE task from a spec using the sequential git branching workflow.

**Use this command** when you're ready to implement the next task in your spec. Each task will be done in its own branch with its own PR targeting the spec branch.

## Before You Begin

Verify:
- Spec has been created and has a draft PR
- Spec branch exists (named without date prefix)
- Previous task PR (if any) has been merged into spec branch

## Task Implementation Process

### Step 1: Verify Spec Branch and Identify Next Task

1. **Determine spec name** from spec folder:
   - Find spec folder: `agent-os/specs/YYYY-MM-DD-spec-name/`
   - Extract branch name: Remove date prefix → `spec-name`

2. **Check current git branch**:
   ```bash
   git branch --show-current
   ```

   **If on spec branch**: Continue to next step

   **If on main/master**:
   ```bash
   git checkout [spec-name]
   ```

   **If on task branch**: **STOP** and display:
   ```
   ⚠️  Currently on task branch: [branch-name]

   You need to:
   1. Finish the current task
   2. Create and merge its PR
   3. Switch back to the spec branch

   Then run this command again.
   ```

3. **Pull latest from spec branch**:
   ```bash
   git pull origin [spec-name]
   ```

4. **Identify next task**:
   - Open `agent-os/specs/[spec-folder]/tasks.md`
   - Find first task group without `[x]` checkbox
   - Note task number and title

5. **Confirm with user**:
   ```
   Ready to implement: **Task [N]: [Task Title]**

   This task includes these subtasks:
   - [ ] [subtask 1]
   - [ ] [subtask 2]
   - [ ] [subtask 3]

   Shall I proceed with implementing this task? (yes/no)
   ```

   **WAIT for explicit user confirmation**. Do not proceed without "yes".

### Step 2: Create Task Branch

1. **Create branch name**:
   - Format: `[task-number]-[task-name-slug]`
   - Example: Task "1: Database Schema Updates" → `1-database-schema-updates`

2. **Create and checkout task branch**:
   ```bash
   git checkout -b [task-number]-[task-name-slug]
   ```

   You are now on the task branch, ready to implement.

### Step 3: Implement the Task

Follow the implementation workflow:

```
Implement all tasks assigned to you in your task group.

Focus ONLY on implementing the areas that align with **areas of specialization** (your "areas of specialization" are defined above).

Guide your implementation using:
- **The existing patterns** that you've found and analyzed.
- **User Standards & Preferences** which are defined below.

Self-verify and test your work by:
- Running ONLY the tests you've written (if any) and ensuring those tests pass.
- IF your task involves user-facing UI, and IF you have access to browser testing tools, open a browser and use the feature you've implemented as if you are a user to ensure a user can use the feature in the intended way.

```

**Key steps**:
- Write tests first (TDD)
- Implement functionality
- Ensure all tests pass
- Run `composer ready` and fix any issues
- Check off subtasks in `tasks.md` as you complete them
- Document your work

### Step 4: Document Implementation

Follow the documentation workflow:

```
Using the task number and task title that's been assigned to you, create a file in the current spec's `implementation` folder called `[task-number]-[task-title]-implementation.md`.

For example, if you've been assigned implement the 3rd task from `tasks.md` and that task's title is "Commenting System", then you must create the file: `agent-os/specs/[this-spec]/implementation/3-commenting-system-implementation.md`.

Use the following structure for the content of your implementation documentation:

```markdown
# Task [number]: [Task Title]

## Overview
**Task Reference:** Task #[number] from `agent-os/specs/[this-spec]/tasks.md`
**Implemented By:** [Agent Role/Name]
**Date:** [Implementation Date]
**Status:** ✅ Complete | ⚠️ Partial | 🔄 In Progress

### Task Description
[Brief description of what this task was supposed to accomplish]

## Implementation Summary
[High-level overview of the solution implemented - 2-3 short paragraphs explaining the approach taken and why]

## Files Changed/Created

### New Files
- `path/to/file.ext` - [1 short sentence description of purpose]
- `path/to/another/file.ext` - [1 short sentence description of purpose]

### Modified Files
- `path/to/existing/file.ext` - [1 short sentence on what was changed and why]
- `path/to/another/existing/file.ext` - [1 short sentence on what was changed and why]

### Deleted Files
- `path/to/removed/file.ext` - [1 short sentence on why it was removed]

## Key Implementation Details

### [Component/Feature 1]
**Location:** `path/to/file.ext`

[Detailed explanation of this implementation aspect]

**Rationale:** [Why this approach was chosen]

### [Component/Feature 2]
**Location:** `path/to/file.ext`

[Detailed explanation of this implementation aspect]

**Rationale:** [Why this approach was chosen]

## Database Changes (if applicable)

### Migrations
- `[timestamp]_[migration_name].rb` - [What it does]
  - Added tables: [list]
  - Modified tables: [list]
  - Added columns: [list]
  - Added indexes: [list]

### Schema Impact
[Description of how the schema changed and any data implications]

## Dependencies (if applicable)

### New Dependencies Added
- `package-name` (version) - [Purpose/reason for adding]
- `another-package` (version) - [Purpose/reason for adding]

### Configuration Changes
- [Any environment variables, config files, or settings that changed]

## Testing

### Test Files Created/Updated
- `path/to/test/file_spec.rb` - [What is being tested]
- `path/to/feature/test_spec.rb` - [What is being tested]

### Test Coverage
- Unit tests: [✅ Complete | ⚠️ Partial | ❌ None]
- Integration tests: [✅ Complete | ⚠️ Partial | ❌ None]
- Edge cases covered: [List key edge cases tested]

### Manual Testing Performed
[Description of any manual testing done, including steps to verify the implementation]

## User Standards & Preferences Compliance

In your instructions, you were provided with specific user standards and preferences files under the "User Standards & Preferences Compliance" section. Document how your implementation complies with those standards.

Keep it brief and focus only on the specific standards files that were applicable to your implementation tasks.

For each RELEVANT standards file you were instructed to follow:

### [Standard/Preference File Name]
**File Reference:** `path/to/standards/file.md`

**How Your Implementation Complies:**
[1-2 Sentences to explain specifically how your implementation adheres to the guidelines, patterns, or preferences outlined in this standards file. Include concrete examples from your code.]

**Deviations (if any):**
[If you deviated from any standards in this file, explain what, why, and what the trade-offs were]

---

*Repeat the above structure for each RELEVANT standards file you were instructed to follow*

## Integration Points (if applicable)

### APIs/Endpoints
- `[HTTP Method] /path/to/endpoint` - [Purpose]
  - Request format: [Description]
  - Response format: [Description]

### External Services
- [Any external services or APIs integrated]

### Internal Dependencies
- [Other components/modules this implementation depends on or interacts with]

## Known Issues & Limitations

### Issues
1. **[Issue Title]**
   - Description: [What the issue is]
   - Impact: [How significant/what it affects]
   - Workaround: [If any]
   - Tracking: [Link to issue/ticket if applicable]

### Limitations
1. **[Limitation Title]**
   - Description: [What the limitation is]
   - Reason: [Why this limitation exists]
   - Future Consideration: [How this might be addressed later]

## Performance Considerations
[Any performance implications, optimizations made, or areas that might need optimization]

## Security Considerations
[Any security measures implemented, potential vulnerabilities addressed, or security notes]

## Dependencies for Other Tasks
[List any other tasks from the spec that depend on this implementation]

## Notes
[Any additional notes, observations, or context that might be helpful for future reference]
```

```

Create implementation report at:
`agent-os/specs/[spec-folder]/implementation/[task-number]-[task-name].md`

### Step 5: Update Tasks List

Follow the update workflow:

```
In the current spec's `tasks.md` find YOUR task group that's been assigned to YOU and update this task group's parent task and sub-task(s) checked statuses to complete for the specific task(s) that you've implemented.

Mark your task group's parent task and sub-task as complete by changing its checkbox to `- [x]`.

DO NOT update task checkboxes for other task groups that were NOT assigned to you for implementation.

```

Mark the parent task and all subtasks as complete in `tasks.md`.

### Step 6: Commit Changes

1. **Stage all changes**:
   ```bash
   git add .
   ```

2. **Commit with descriptive message**:
   ```bash
   git commit -m "Complete Task [N]: [Task Title]

   [Brief 1-2 sentence description of what was implemented]

   Subtasks completed:
   - [subtask 1]
   - [subtask 2]
   - [subtask 3]

   Tests: All passing
   Code quality: composer ready passes

   🤖 Generated with Claude Code (https://claude.com/claude-code)

   Co-Authored-By: Claude <noreply@anthropic.com>"
   ```

### Step 7: Push Task Branch

```bash
git push -u origin [task-number]-[task-name-slug]
```

### Step 8: Create PR Targeting Spec Branch

**Important**: The PR must target the **spec branch**, NOT main.

1. **Get spec PR number** (for reference in task PR):
   ```bash
   gh pr list --head [spec-name] --json number --jq '.[0].number'
   ```

2. **Create PR**:
   ```bash
   gh pr create \
     --base [spec-name] \
     --title "Task [N]: [Task Title]" \
     --body "$(cat <<'EOF'
   ## Task Overview

   Implements Task [N] from the [spec-name] specification.

   ### What Was Implemented

   [Brief description of changes]

   ### Key Changes

   **Files Created:**
   - [list new files]

   **Files Modified:**
   - [list modified files]

   **Database Changes:**
   - [list migrations/schema changes, or "None"]

   ### Testing

   - [x] All new tests pass
   - [x] All existing tests pass
   - [x] Manual testing completed
   - [x] `composer ready` passes (no lint/type errors)

   ### Documentation

   - [x] Implementation documented in `implementation/[task-number]-[task-name].md`
   - [x] Tasks list updated with completion checkmarks

   ---

   **Part of spec PR:** #[spec-pr-number]

   🤖 Generated with Claude Code (https://claude.com/claude-code)
   EOF
   )"
   ```

   This creates a **regular PR** (not draft) targeting the spec branch.

### Step 9: Return to Spec Branch

After creating the PR, switch back to spec branch:

```bash
git checkout [spec-name]
```

This keeps you positioned correctly for the next task.

### Step 10: Notify User

Display this message:

```
✅ Task [N] Complete: [Task Title]

**Task Branch:** [task-number]-[task-name-slug]
**Pull Request:** [PR-URL]
**Targets:** [spec-name] branch (NOT main)

### Summary

[Brief summary from implementation report]

### Files Changed

**Created:**
- [list]

**Modified:**
- [list]

### Testing Status

✓ All tests passing
✓ Code quality checks passed
✓ Implementation documented

### Next Steps

1. **Review the PR** at [PR-URL]
   - This PR shows only the changes for this specific task
   - Easy to review in isolation

2. **Merge the PR** into the [spec-name] branch when satisfied

3. **Ready for next task?** Let me know when you've merged and I'll implement the next task

### Progress

**Completed:** [N] of [Total] tasks
**Remaining:** [X] tasks
```

**STOP HERE**. Wait for user to review, merge, and request the next task.

## User Standards Compliance

Ensure all implementation follows:

@agent-os/standards/backend/api.md
@agent-os/standards/backend/migrations.md
@agent-os/standards/backend/models.md
@agent-os/standards/backend/queries.md
@agent-os/standards/frontend/accessibility.md
@agent-os/standards/frontend/components.md
@agent-os/standards/frontend/css.md
@agent-os/standards/frontend/responsive.md
@agent-os/standards/global/architecture.md
@agent-os/standards/global/coding-style.md
@agent-os/standards/global/commenting.md
@agent-os/standards/global/conventions.md
@agent-os/standards/global/error-handling.md
@agent-os/standards/global/naming.md
@agent-os/standards/global/tech-stack.md
@agent-os/standards/global/validation.md
@agent-os/standards/testing/test-writing.md

## Repeat for Next Task

When user indicates they've merged the PR and are ready for the next task, run this command again. It will:
1. Pull the latest from spec branch (including the just-merged task)
2. Identify the next uncompleted task
3. Create a new task branch
4. Implement the next task
5. Create another PR targeting spec branch

Continue this cycle until all tasks in `tasks.md` are complete.
