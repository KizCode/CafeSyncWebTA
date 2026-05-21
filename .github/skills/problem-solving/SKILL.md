---
name: problem-solving
user-invocable: true
description: "Guide a developer through a structured coding problem-solving workflow for bugs, feature work, and design decisions."
argument-hint: What coding problem or task do you want to solve?
maintainers:
  - repo-maintainers
---

# Problem Solving

Use this skill to produce a concise, reproducible plan for diagnosing and fixing coding problems (bugs, feature design, or unclear behavior).

**Scope**: workspace-scoped by default. If you prefer a personal workflow variation, indicate that when invoking the skill.

## Workflow

1. Clarify the goal
    - Restate the requested outcome in one sentence.
    - Ask up to 3 focused clarifying questions for any ambiguous constraints or failure modes.
2. Inspect the context
    - Locate relevant files, routes, and functions; list them.
    - Note repo conventions, existing tests, and prior related changes.
3. Analyze the problem
    - Reproduce or reason about the symptom and capture minimal repro steps.
    - Distinguish symptom vs root cause; propose hypotheses.
4. Propose options
    - Give 2–3 options: quick fix, robust fix, and a tradeoff summary for each.
    - Recommend a single preferred option and explain why.
5. Implementation plan
    - Break the chosen option into clear, verifiable steps (code edits, tests, migrations).
    - Provide example diffs or code snippets when helpful.
6. Verify and iterate
    - Describe tests to run or manual checks to perform.
    - If verification fails, repeat analysis with collected evidence.

## Decision Points

- Ambiguity: pause and ask focused questions rather than guessing.
- Style/Pattern: follow existing repository conventions unless there is a clear, documented reason to change them.
- Risk: prefer incremental, reversible changes for production code; prefer branch-and-PR flow for larger changes.

## Completion Criteria (Definition of Done)

- The desired behavior is restated and agreed.
- A small, safe change or a clear multi-step plan is produced.
- Changes are consistent with repository style and include tests or verification steps where applicable.
- The author (or reviewer) can follow the implementation plan and reproduce verification steps.

## Inputs and Outputs

- Input: natural-language description of the problem, relevant file paths, and any additional constraints.
- Output: (a) Clarifying questions if needed, or (b) a short plan with steps, proposed code snippets, and verification checks.

## Ambiguities to Resolve (prompts to ask the user)

- Is this change intended for a quick hotfix or a long-term solution?
- Should the fix prioritize minimal disruption or correctness/performance?
- Are there any deployment or backwards-compatibility constraints?

## Example Prompts

- Help me fix inconsistent totals in the transaction report — I can share the controller and migration files.
- Create a minimal PR plan to make profile updates validate phone numbers correctly.
- Walk me through debugging a failing feature test for checkout flow.

## Maintenance

- File location: .github/skills/problem-solving/SKILL.md
- Owner: repo maintainers by default; add a `maintainers:` frontmatter line if you want explicit ownership.

## Reviewer checklist

- [ ] Confirm the user goal was restated accurately.
- [ ] Verify the proposed fix follows repo conventions.
- [ ] Ensure verification steps are clear and actionable.
- [ ] Check that any tradeoffs or alternatives are documented.

---

Revision notes: expanded workflow, added decision points, inputs/outputs, and clarifying questions.
