export function listError(
    errors: Record<string, string | undefined>,
    field: string,
): string | undefined {
    return (
        errors[field] ??
        Object.entries(errors).find(([key]) => key.startsWith(`${field}[`))?.[1]
    );
}
