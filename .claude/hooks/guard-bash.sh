#!/usr/bin/env bash
#
# PreToolUse guard for the Bash tool.
#
# The deny rules in settings.json cover the file tools, but a shell command can
# read a file that Read is denied (`cat .env`) and can reach git through
# spellings a prefix rule does not match (`git --no-pager commit`). This closes
# both paths.
#
# It is a guardrail against an accidental slip, not a sandbox: a determined
# bypass (base64, a helper script, an alias) is not caught.

payload=$(cat)
command=$(printf '%s' "$payload" | jq -r '.tool_input.command // empty')

[ -n "$command" ] || exit 0

deny() {
    jq -nc --arg reason "$1" '{
        hookSpecificOutput: {
            hookEventName: "PreToolUse",
            permissionDecision: "deny",
            permissionDecisionReason: $reason
        }
    }'
    exit 0
}

# `.env.example` is removed before the search: the template carries no values,
# and it is the only supported way to document a variable.
probe=$(printf '%s' "$command" | sed 's/\.env\.example//g')

case "$probe" in
    *.env*)
        deny "This command references an environment file. Reading or writing .env is not allowed — document variables in .env.example instead."
        ;;
esac

if printf '%s' "$command" \
    | grep -qE '(^|[;&|(]|[[:space:]])git[[:space:]]+(-[^[:space:]]+[[:space:]]+)*(commit|push)([[:space:]]|;|$)'; then
    deny "Committing and pushing belong to the maintainer. Leave the work staged and unpushed, and say what is ready."
fi

exit 0
