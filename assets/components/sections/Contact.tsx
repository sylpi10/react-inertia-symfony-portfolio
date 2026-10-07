import { useForm, usePage } from "@inertiajs/react";
import { Audience } from "../../types/audience";
import { budgets, deadlines, projectTypes } from "../../types/quote";

// audience : page d'origine, reprise dans l'objet du mail et pour la redirection
export default function Contact({ audience }: { audience: Audience }) {
    const form = useForm({
        name: "",
        email: "",
        message: "",
        website: "",
        audience,
        // demande de devis : champs affichés en mode création de site uniquement
        projectType: "",
        budget: "",
        deadline: "",
    });
    // choix vide envoyé en null : le serveur attend une valeur de la liste ou rien
    form.transform((data) => ({
        ...data,
        projectType: data.projectType || null,
        budget: data.budget || null,
        deadline: data.deadline || null,
    }));
    const { flash } = usePage();

    const submit = (e: React.SubmitEvent) => {
        e.preventDefault();
        form.post("/contact", {
            preserveScroll: true, // on reste sur #contact
            onSuccess: () => form.reset(),
        });
        return null;
    };

    return (
        <div className="section-container">
            <div className="section-wrapper contact-wrapper">
                <h2 className={"section-title"}>On discute ?</h2>
                <div className="contact-form">
                    <div className="status-wrapper">
                        {/*{flash.loading && <div className="alert loading">Envoi du Message...</div>}*/}
                        {flash.error && (
                            <div className="alert alert-error">
                                {flash.error}
                            </div>
                        )}
                        {flash.success && (
                            <div className="alert alert-success">
                                {flash.success}
                            </div>
                        )}
                    </div>

                    <form onSubmit={submit}>
                        <div className={"input-wrapper"}>
                            <input
                                type="text"
                                value={form.data.name}
                                id="name"
                                name="name"
                                autoComplete="name"
                                placeholder=""
                                onChange={(e) =>
                                    form.setData("name", e.target.value)
                                }
                            />
                            <label htmlFor="name">Votre Nom</label>
                            {form.errors.name && (
                                <div className="form-error">
                                    {form.errors.name}
                                </div>
                            )}
                        </div>
                        <div className={"input-wrapper"}>
                            <input
                                type="email"
                                value={form.data.email}
                                id="email"
                                name="email"
                                autoComplete="email"
                                placeholder=""
                                onChange={(e) =>
                                    form.setData("email", e.target.value)
                                }
                            />
                            <label htmlFor="email">Votre Email</label>
                            {form.errors.email && (
                                <div className="form-error">
                                    {form.errors.email}
                                </div>
                            )}
                        </div>
                        {audience === "client" && (
                            <div className="quote-fields">
                                <QuoteSelect
                                    id="projectType"
                                    label="Type de projet"
                                    options={projectTypes}
                                    value={form.data.projectType}
                                    error={form.errors.projectType}
                                    onChange={(value) =>
                                        form.setData("projectType", value)
                                    }
                                />
                                <QuoteSelect
                                    id="budget"
                                    label="Budget indicatif (facultatif)"
                                    options={budgets}
                                    value={form.data.budget}
                                    error={form.errors.budget}
                                    onChange={(value) =>
                                        form.setData("budget", value)
                                    }
                                />
                                <QuoteSelect
                                    id="deadline"
                                    label="Délai souhaité"
                                    options={deadlines}
                                    value={form.data.deadline}
                                    error={form.errors.deadline}
                                    onChange={(value) =>
                                        form.setData("deadline", value)
                                    }
                                />
                            </div>
                        )}
                        <div className={"input-wrapper"}>
                            <textarea
                                name="message"
                                id="message"
                                autoComplete="off"
                                placeholder=""
                                value={form.data.message}
                                onChange={(e) =>
                                    form.setData("message", e.target.value)
                                }
                            ></textarea>
                            <label htmlFor="message">Votre Message</label>
                            {form.errors.message && (
                                <div className="form-error">
                                    {form.errors.message}
                                </div>
                            )}
                        </div>
                        <div className={"input-wrapper"}>
                            <input
                                type="text"
                                id="website"
                                name="website"
                                // placeholder="Site web"
                                className="hidden"
                                autoComplete="off"
                                value={form.data.website}
                                onChange={(e) =>
                                    form.setData("website", e.target.value)
                                }
                            />
                        </div>
                        <button
                            className={"button-link email-button"}
                            type="submit"
                        >
                            <span className={"see-more sent-mail"}>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="26"
                                    height="26"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    // stroke-width="1.6"
                                    // stroke-linecap="round"
                                    // stroke-linejoin="round"
                                    className="lucide lucide-send-icon lucide-send"
                                >
                                    <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                                    <path d="m21.854 2.147-10.94 10.939" />
                                </svg>
                                Envoyer
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}

// liste déroulante de la demande de devis, libellé au-dessus (pas de label flottant)
function QuoteSelect({
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
            >
                <option value="">Choisir…</option>
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>
            {error && <div className="form-error">{error}</div>}
        </div>
    );
}
