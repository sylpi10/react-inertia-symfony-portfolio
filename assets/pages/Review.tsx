import { useForm, usePage } from "@inertiajs/react";
import EmainIcon from "../components/ui/EmainIcon";

type ReviewProject = { id: number; name: string };

type ReviewForm = {
    author: string;
    authorRole: string;
    text: string;
    projects: number[];
    consent: boolean;
    website: string;
};

// page à part, dont le lien est envoyé aux clients ; les projets proposés
// sont ceux qui n'ont pas encore d'avis
export default function Review({ projects }: { projects: ReviewProject[] }) {
    const form = useForm<ReviewForm>({
        author: "",
        authorRole: "",
        text: "",
        // un seul projet : coché d'office
        projects: projects.length === 1 ? [projects[0].id] : [],
        consent: false,
        website: "",
    });
    const { flash } = usePage();
    // erreur sur la liste ou sur un de ses éléments (projects[0]…)
    const errors = form.errors as Record<string, string | undefined>;
    const projectsError =
        errors.projects ??
        Object.entries(errors).find(([key]) =>
            key.startsWith("projects["),
        )?.[1];

    const toggleProject = (id: number, checked: boolean) =>
        form.setData(
            "projects",
            checked
                ? [...form.data.projects, id]
                : form.data.projects.filter((p) => p !== id),
        );

    const submit = (e: React.SubmitEvent) => {
        e.preventDefault();
        form.post("/avis", {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    };

    return (
        <div className="section-container review-page">
            <div className="section-wrapper contact-wrapper">
                <h1 className="section-title">Votre avis</h1>
                <p className="review-intro">
                    Merci d’avoir travaillé avec moi ! Quelques mots sur notre
                    collaboration aident d’autres clients à se décider.
                </p>
                <div className="contact-form">
                    {/* zone toujours présente : les messages qui y apparaissent sont lus */}
                    <div className="status-wrapper" aria-live="polite">
                        {flash.error && (
                            <div className="alert alert-error" role="alert">
                                {flash.error}
                            </div>
                        )}
                        {flash.success && (
                            <div className="alert alert-success">
                                {flash.success}
                            </div>
                        )}
                    </div>

                    {projects.length === 0 ? (
                        <p className="review-empty">
                            Tous les projets ont déjà reçu un avis.
                        </p>
                    ) : (
                        <form onSubmit={submit}>
                            <div className="input-wrapper">
                                <input
                                    type="text"
                                    value={form.data.author}
                                    id="author"
                                    name="author"
                                    autoComplete="name"
                                    placeholder=""
                                    onChange={(e) =>
                                        form.setData("author", e.target.value)
                                    }
                                    aria-invalid={!!form.errors.author}
                                    aria-describedby={
                                        form.errors.author
                                            ? "author-error"
                                            : "author-help"
                                    }
                                />
                                <label htmlFor="author">Votre nom</label>
                                <div className="form-help" id="author-help">
                                    Affiché avec l’avis : prénom seul ou nom
                                    complet, comme vous préférez.
                                </div>
                                {form.errors.author && (
                                    <div
                                        className="form-error"
                                        id="author-error"
                                    >
                                        {form.errors.author}
                                    </div>
                                )}
                            </div>

                            <div className="input-wrapper">
                                <input
                                    type="text"
                                    value={form.data.authorRole}
                                    id="authorRole"
                                    name="authorRole"
                                    autoComplete="organization-title"
                                    placeholder=""
                                    maxLength={100}
                                    onChange={(e) =>
                                        form.setData(
                                            "authorRole",
                                            e.target.value,
                                        )
                                    }
                                    aria-invalid={!!form.errors.authorRole}
                                    aria-describedby={
                                        form.errors.authorRole
                                            ? "authorRole-error"
                                            : "authorRole-help"
                                    }
                                />
                                <label htmlFor="authorRole">
                                    Poste ou entreprise (facultatif)
                                </label>
                                <div className="form-help" id="authorRole-help">
                                    Affiché sous votre nom.
                                </div>
                                {form.errors.authorRole && (
                                    <div
                                        className="form-error"
                                        id="authorRole-error"
                                    >
                                        {form.errors.authorRole}
                                    </div>
                                )}
                            </div>

                            <fieldset
                                className="review-projects"
                                aria-invalid={!!projectsError}
                                aria-describedby={
                                    projectsError ? "projects-error" : undefined
                                }
                            >
                                <legend>Projet(s) concerné(s)</legend>
                                {projects.map((project) => (
                                    <label
                                        key={project.id}
                                        className="checkbox-wrapper"
                                    >
                                        <input
                                            type="checkbox"
                                            name="projects"
                                            value={project.id}
                                            checked={form.data.projects.includes(
                                                project.id,
                                            )}
                                            onChange={(e) =>
                                                toggleProject(
                                                    project.id,
                                                    e.target.checked,
                                                )
                                            }
                                        />
                                        {project.name}
                                    </label>
                                ))}
                                {projectsError && (
                                    <div
                                        className="form-error"
                                        id="projects-error"
                                    >
                                        {projectsError}
                                    </div>
                                )}
                            </fieldset>

                            <div className="input-wrapper">
                                <textarea
                                    name="text"
                                    id="text"
                                    autoComplete="off"
                                    placeholder=""
                                    maxLength={1000}
                                    value={form.data.text}
                                    onChange={(e) =>
                                        form.setData("text", e.target.value)
                                    }
                                    aria-invalid={!!form.errors.text}
                                    aria-describedby={
                                        form.errors.text
                                            ? "text-error"
                                            : undefined
                                    }
                                ></textarea>
                                <label htmlFor="text">Votre avis</label>
                                {form.errors.text && (
                                    <div className="form-error" id="text-error">
                                        {form.errors.text}
                                    </div>
                                )}
                            </div>

                            <div className="consent-wrapper">
                                <label className="checkbox-wrapper">
                                    <input
                                        type="checkbox"
                                        name="consent"
                                        checked={form.data.consent}
                                        onChange={(e) =>
                                            form.setData(
                                                "consent",
                                                e.target.checked,
                                            )
                                        }
                                        aria-invalid={!!form.errors.consent}
                                        aria-describedby={
                                            form.errors.consent
                                                ? "consent-error"
                                                : undefined
                                        }
                                    />
                                    J’accepte que mon avis et mon nom soient
                                    publiés sur sylvainpillet.com. Je peux
                                    demander son retrait à tout moment.
                                </label>
                                {form.errors.consent && (
                                    <div
                                        className="form-error"
                                        id="consent-error"
                                    >
                                        {form.errors.consent}
                                    </div>
                                )}
                            </div>

                            <div className="input-wrapper">
                                <input
                                    type="text"
                                    id="website"
                                    name="website"
                                    className="hidden"
                                    autoComplete="off"
                                    tabIndex={-1}
                                    value={form.data.website}
                                    onChange={(e) =>
                                        form.setData("website", e.target.value)
                                    }
                                />
                            </div>
                            <button
                                className={"button-link email-button"}
                                type="submit"
                                disabled={form.processing}
                            >
                                <span className={"see-more sent-mail"}>
                                    <EmainIcon />
                                    Envoyer
                                </span>
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
