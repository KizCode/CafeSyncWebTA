---
name: problem-solving
user-invocable: true
description: "Guide a developer through a structured coding problem-solving workflow for bugs, feature work, and design decisions."
argument-hint: What coding problem or task do you want to solve?
---

# Problem Solving

Use this skill when you want a repeatable, structured approach to understand a problem, evaluate options, and produce a clear fix plan.

## Workflow

1. Clarify the goal
    - Restate the requested outcome in your own words.
    - Confirm any missing constraints, edge cases, or accepted failure modes.
2. Inspect the context
    - Identify the relevant files, functions, and data flow.
    - Check for existing patterns, conventions, and prior solutions in the repo.
3. Analyze the problem
    - Pinpoint where the behavior diverges from the goal.
    - Separate symptoms from root cause.
4. Propose a solution
    - Choose the smallest change that solves the problem safely.
    - Call out tradeoffs and alternatives if more than one viable path exists.
5. Implement and verify
    - Edit the code with clear, minimal changes.
    - Validate against tests, examples, or user-facing behavior.

## Decision Points

- If the problem is ambiguous, ask a focused clarifying question before you modify files.
- If there is an existing repo pattern, follow it rather than introducing a new style.
- Prefer tiny, incremental changes for bug fixes; prefer plan-based changes for larger feature work.

## Completion Criteria

- The requested behavior is clearly described and supported by the proposed implementation.
- The fix does not introduce inconsistent style or break existing patterns in the repo.
- The code change is verified by available tests or by reasoning about the expected output.

## Example Prompts

- `Help me solve a validation bug in the profile update flow.`
- `Create a plan for fixing the transaction total calculation.`
- `Guide me through debugging this button action in the Laravel views.`
