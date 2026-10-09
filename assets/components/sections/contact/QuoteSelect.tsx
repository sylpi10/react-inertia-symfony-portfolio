// liste déroulante de la demande de devis, libellé au-dessus (pas de label flottant)

export default function QuoteSelect({
    id,
    label,
    options,
    value,
    error,
    onChange,
}: {
    id: string;
    label: string;
    options: readonly { value: string; label: string }[];
    value: string;
    error?: string;
    onChange: (value: string) => void;
}) {
    return (
        <div className="select-wrapper">
            <label htmlFor={id}>{label}</label>
            <select
                id={id}
                name={id}
                value={value}
                onChange={(e) => onChange(e.target.value)}
                aria-invalid={!!error}
                aria-describedby={error ? `${id}-error` : undefined}
            >
                <option value="">Choisir…</option>
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
            {error && (
                <div className="form-error" id={`${id}-error`}>
                    {error}
                </div>
            )}
        </div>
    );
}
