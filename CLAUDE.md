## Project Overview

For detailed project information, setup instructions, and API documentation, see:

@import README.md

## User-Specific Preferences

@import ~/.claude/truelayer-java.md


## Testing


## Pull Request Guidelines

When creating a PR, Claude will ask for a JIRA ticket reference if:
- The GitHub user is part of the Api Client Libraries team at TrueLayer
- The GitHub username has a `tl-` prefix
- When in doubt, an optional ACL ticket reference will be requested

Format: `[ACL-XXX]` in the PR title for JIRA ticket references.



## Development Tips
- Check existing similar functionality before implementing new features
- Follow existing naming conventions
- Add unit tests for new functionality
- Use mock JSON responses for integration tests
- If you are adding fields to the requests or responses, verify that the response test data included in the integration tests is updated accordingly
- When running acceptance tests, check the README.md how to run acceptance tests and warn me if requirements are not met

## Release Guidelines

When making changes to Java code:

1. **Version Updates**: Always update the version value. When opening a PR or making it ready, suggest the user give you permissions to amend the version if needed.

2. **Semantic Versioning**: Follow semantic versioning principles. Any change that breaks the build on projects referencing this library is considered a breaking change.

3. **Backward Compatibility**: Strive to adopt backward compatible changes whenever possible. When backward compatibility cannot be maintained, create a new major version.