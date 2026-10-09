import QuoteSelect from "./QuoteSelect";
import { budgets, deadlines, projectTypes } from "../../../types/quote";
import EmainIcon from "../../ui/EmainIcon";
import { InertiaFormProps } from "@inertiajs/react";
import { Audience } from "../../../types/audience";
import { FormProps } from "../../../types/form";
// import { SyntheticEvent } from "react";

type ContactProps = {
    form: InertiaFormProps<FormProps>;
    // submit: SyntheticEvent<HTMLFormElement>;
    submit: any;
    audience: Audience;
};

export default function ContactForm({ form, submit, audience }: ContactProps) {
    return (
        <form onSubmit={submit}>
            <div className={"input-wrapper"}>
                <input
                    type="text"
                    value={form.data.name}
                    id="name"
                    name="name"
                    autoComplete="name"
                    placeholder=""
                    onChange={(e) => form.setData("name", e.target.value)}
                    aria-invalid={!!form.errors.name}
                    aria-describedby={
                        form.errors.name ? "name-error" : undefined
                    }
                />
                <label htmlFor="name">Votre Nom</label>
                {form.errors.name && (
                    <div className="form-error" id="name-error">
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
                    onChange={(e) => form.setData("email", e.target.value)}
                    aria-invalid={!!form.errors.email}
                    aria-describedby={
                        form.errors.email ? "email-error" : undefined
                    }
                />
                <label htmlFor="email">Votre Email</label>
                {form.errors.email && (
                    <div className="form-error" id="email-error">
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
                        onChange={(value) => form.setData("projectType", value)}
                    />
                    <QuoteSelect
                        id="budget"
                        label="Budget indicatif (facultatif)"
                        options={budgets}
                        value={form.data.budget}
                        error={form.errors.budget}
                        onChange={(value) => form.setData("budget", value)}
                    />
                    <QuoteSelect
                        id="deadline"
                        label="Délai souhaité"
                        options={deadlines}
                        value={form.data.deadline}
                        error={form.errors.deadline}
                        onChange={(value) => form.setData("deadline", value)}
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
                    onChange={(e) => form.setData("message", e.target.value)}
                    aria-invalid={!!form.errors.message}
                    aria-describedby={
                        form.errors.message ? "message-error" : undefined
                    }
                ></textarea>
                <label htmlFor="message">Votre Message</label>
                {form.errors.message && (
                    <div className="form-error" id="message-error">
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
                    onChange={(e) => form.setData("website", e.target.value)}
                />
            </div>
            <button className={"button-link email-button"} type="submit">
                <span className={"see-more sent-mail"}>
                    <EmainIcon />
                    Envoyer
                </span>
            </button>
        </form>
    );
}
