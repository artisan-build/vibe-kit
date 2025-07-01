# Centralized AI Agent Instructions

This directory contains the centralized instruction system for all AI agents working on this project.

## Main Instructions

- **`instructions.md`** - Project-wide instructions that apply to all tasks
- **`mutation.md`** - Instructions for mutation testing workflows

## Agent-Specific Instruction Files

The following files have been created to direct various AI agents to use the centralized instructions in this directory:

### IDE and Editor Extensions
- `.cursor/rules.md` - Cursor IDE
- `.vscode/settings.json` - VSCode with GitHub Copilot, Continue, and Codeium
- `.jetbrains/instructions.md` - JetBrains AI Assistant

### Command Line Tools
- `.aider.conf.yml` - Aider configuration
- `.aiderignore` - Aider instructions

### Platform-Specific
- `.continue/config.json` - Continue extension
- `.github/copilot-instructions.md` - GitHub Copilot
- `.replit/instructions.md` - Replit AI
- `.sourcegraph/instructions.md` - Sourcegraph Cody
- `.windsurf/instructions.md` - Windsurf

### AI Service Providers
- `.anthropic/instructions.md` - Anthropic Claude
- `.openai/instructions.md` - OpenAI
- `.claude/instructions.md` - Claude (general)
- `.codeium/instructions.md` - Codeium
- `.supermaven/instructions.md` - Supermaven
- `.tabby/instructions.md` - Tabby
- `.phind/instructions.md` - Phind

### Development Tools
- `.gpt-engineer/instructions.md` - GPT Engineer
- `.devgpt/instructions.md` - DevGPT

### Root Level
- `INSTRUCTIONS.md` - General instructions file that many agents look for

## How It Works

Each agent-specific instruction file contains a simple message directing the agent to:

1. Check the main instructions at `./.agents/instructions.md`
2. Look for task-specific instructions in `./.agents/`
3. Always verify if any existing instructions correspond to the current task

This ensures all AI agents working on the project follow the same guidelines and workflows, regardless of which tool is being used.

## Adding New Instructions

To add new task-specific instructions:

1. Create a new `.md` file in this directory
2. Follow the same format as existing instruction files
3. Include appropriate frontmatter with description and applicability settings