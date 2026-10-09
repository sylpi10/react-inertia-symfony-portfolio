import { projectImageUrl } from "../../../lib/images";
import { Audience } from "../../../types/audience";
import { ProjectPreviewProps } from "../../../types/projects";
import CheckIcon from "../../ui/CheckIcon";

export default function ProjectPreview({
    audience,
    project,
}: {
    audience: Audience;
    project: Pick<
        ProjectPreviewProps,
        "detailPic" | "detail_pic_mobile" | "name"
    >;
}) {
    return (
        <>
            <div className="project-preview">
                <div className="preview-infos">
                    <h2>Aperçu visuel du rendu sur ordinateur et sur mobile</h2>
                    {audience === "client" ? (
                        <>
                            <p>
                                Aujourd’hui, la majorité des visites se font
                                depuis un téléphone. Chaque site est donc pensé
                                pour tous les écrans dès le départ.
                            </p>
                            <ul>
                                <li>
                                    <CheckIcon />
                                    Mise en page qui se réorganise selon la
                                    taille de l’écran.
                                </li>
                                <li>
                                    <CheckIcon />
                                    Textes lisibles sans zoomer, des boutons
                                    faciles à toucher du doigt.
                                </li>
                                <li>
                                    <CheckIcon />
                                    Images allégées pour s’afficher vite, même
                                    avec une connexion mobile.
                                </li>
                                <li>
                                    <CheckIcon />
                                    Menu et formulaires simples à utiliser sur
                                    téléphone.
                                </li>
                            </ul>
                        </>
                    ) : (
                        <>
                            <p>
                                Intégration responsive: mise en page fluide en
                                Flexbox et Grid, typographie et espacements
                                adaptatifs.
                                <p>
                                    Images en WebP, servies à la bonne taille
                                    selon l’écran. Zones tactiles dimensionnées
                                    pour le mobile, intégration fidèle aux
                                    maquettes.
                                </p>
                                <p>
                                    Structure Html validée selon les standards
                                    W3C et optimisée pour l'accessibilité.
                                </p>
                            </p>
                        </>
                    )}
                </div>
                <div className="preview-images-wrapper">
                    <div className="computer-images-wrapper">
                        <div className="computer-container">
                            {/* capture qui défile : atteignable au clavier pour la faire défiler aux flèches */}
                            <div
                                className="computer-img-container"
                                tabIndex={0}
                                role="region"
                                aria-label="Capture ordinateur, défilable"
                            >
                                <img
                                    src={projectImageUrl(project.detailPic)}
                                    className="project-image"
                                    alt={`Aperçu du site ${project.name} sur ordinateur`}
                                    width="800"
                                    height="1000"
                                    decoding="async"
                                />
                            </div>
                        </div>
                    </div>
                    <div className="mobile-images-wrapper">
                        <div className="mobile-container">
                            <div
                                className="mobile-img-container"
                                tabIndex={0}
                                role="region"
                                aria-label="Capture mobile, défilable"
                            >
                                <img
                                    src={projectImageUrl(
                                        project.detail_pic_mobile ??
                                            project.detailPic,
                                    )}
                                    className="project-image"
                                    alt={`Aperçu du site ${project.name} sur mobile`}
                                    width="300"
                                    height="600"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
