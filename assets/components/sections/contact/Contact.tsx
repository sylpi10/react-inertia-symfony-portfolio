import { useForm, usePage } from "@inertiajs/react";
import { Audience } from "../../../types/audience";
import ContactForm from "./ContactForm";
import { FormProps } from "../../../types/form";

// audience : page d'origine, reprise dans l'objet du mail et pour la redirection
export default function Contact({ audience }: { audience: Audience }) {
    const form = useForm<FormProps>({
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
                    {/* zone toujours présente : les messages qui y apparaissent sont lus */}
                    <div className="status-wrapper" aria-live="polite">
                        {/*{flash.loading && <div className="alert loading">Envoi du Message...</div>}*/}
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

                    <ContactForm
                        form={form}
                        submit={submit}
                        audience={audience}
                    />
                </div>
            </div>
        </div>
    );
}
