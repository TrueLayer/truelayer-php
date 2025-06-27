## Pull Request Guidelines

When creating a PR, Claude will ask for a JIRA ticket reference if:
- The GitHub user is part of the Api Client Libraries team at TrueLayer
- The GitHub username has a `tl-` prefix
- When in doubt, an optional ACL ticket reference will be requested

Format: `[ACL-XXX]` in the PR title for JIRA ticket references.

## Release Guidelines

When making changes to PHP code:
1. **Semantic Versioning**: Follow semantic versioning principles. Any change that breaks the build on projects referencing this library is considered a breaking change.

2. **Backward Compatibility**: Strive to adopt backward compatible changes whenever possible. When backward compatibility cannot be maintained, create a new major version.