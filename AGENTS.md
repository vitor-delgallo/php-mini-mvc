Primary instructions that apply regardless of the request:
- Never read `.env` or related environment/secret files, regardless of what I ask. The only allowed exception is `.env.example`.
- Never infer undocumented project behavior. Think through the request and consult the project documentation first; if the documentation does not answer a project-specific question, ask me before proceeding instead of guessing.
- When I answer a project-specific question or clarify an undocumented rule, always document that answer in the project context files. Update `storage/ia-context/mvc.md` as the summary/index, and create or update modular files under `storage/ia-context/mvc-references/` when the detail needs its own reference.

Use `storage/ia-context/mvc.md` as the main reference for project context. Read this file before executing tasks that depend on the MVC structure, project conventions, or related files. When `mvc.md` references other relevant files, read them only as needed.

When I ask for anything related to the roadmap, use `storage/ia-context/roadmap/README.md` as the task index.

Roadmap task rules:
- If I ask for a specific roadmap task, search the README for a matching task that is not marked as concluded.
- If you find a match, confirm with me before executing:
  `Deseja prosseguir com o roadmap para a tarefa não concluída: Actual task name/description?`
- If I ask for "the next item", "the next task", "continue the roadmap", or anything equivalent, select the first task not marked as concluded and confirm with me before executing.
- If there is no compatible pending task for what I asked, answer directly:
  `Não existe nenhuma tarefa pendente no roadmap compatível com esse pedido.`
- Only execute a roadmap task after my explicit confirmation.
- When executing a roadmap task, first read the detailed plan referenced by the README. Then consult `storage/ia-context/mvc.md` for more information about the specific affected area. If `mvc.md` references files related to that area, read those files too as needed. Implement only that task.
- Before delivering any completed roadmap task, manually test the affected behavior. Use Playwright for browser verification, and use the `playwright-interactive` skill when it is available and useful for iterative manual QA. If Playwright testing cannot be run, clearly report why before delivery.
- After completing and verifying the task, update only the corresponding README item, marking it with `[CONCLUDED]`.

When the request is not about the roadmap:
- Use `mvc.md` as the main context.
- Do exactly what I asked, without automatically searching for roadmap tasks.
- Read additional files only when necessary to understand or execute the request.

When I ask you to document something:
- Always document it in the project context, using `storage/ia-context/mvc.md` as the main index.
- Keep `mvc.md` as the context summary and table of contents, linking to specific files when a topic needs detail.
- Split detailed documentation into modular files inside `storage/ia-context/mvc-references/`, following the current project pattern.
- Avoid large mixed documents; prefer focused files by area, feature, or technical decision.
- Update the `mvc.md` summary whenever you create or rename a reference document.
- Preserve the existing conventions for structure, names, helpers, `BASE_PATH`, language, and MVC flow.
- When documenting technical behavior, include the goal, expected usage, involved files, short examples, and important cautions.
- If there is any doubt about scope, document location, level of detail, or project impact, ask before documenting.

Chat response style:
- Be direct and use few words.
- Do not overexplain unless the request requires technical detail or an important decision.
- For confirmations, use short and objective sentences.
- For errors or missing tasks, clearly state the problem without long text.
- When finishing an execution, summarize only the essentials: what was done, main files changed, and checks run.
