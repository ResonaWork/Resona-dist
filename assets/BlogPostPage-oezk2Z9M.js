import{x as g,r as a,j as e,L as f,z as h,e as u,d as b}from"./index-v9YSdgyP.js";import{S as j}from"./SEO-DGLMjagV.js";const w=()=>{const{slug:o}=g(),[t,c]=a.useState(null),[m,l]=a.useState(!0),[i,p]=a.useState(null);if(a.useEffect(()=>{(async()=>{if(o){l(!0);try{const s=await h.getBySlug(o);if(!s)throw new Error("Post não encontrado.");c(s)}catch(s){p(u(s)),console.error(s)}finally{l(!1)}}})()},[o]),m)return e.jsx("div",{className:"flex justify-center items-center h-screen bg-dark text-white",children:"Carregando..."});if(i||!t)return e.jsxs("div",{className:"flex flex-col justify-center items-center h-screen bg-dark text-red-500",children:[e.jsx("h2",{className:"text-2xl font-bold",children:"Post não encontrado"}),e.jsx("p",{className:"mt-2",children:i}),e.jsx(f,{to:"/blog",className:"mt-4 text-primary underline",children:"Voltar para o blog"})]});const d=new Date(t.created_at).toLocaleDateString("pt-BR",{day:"2-digit",month:"long",year:"numeric"}),n=(()=>{if(t.image_url){const{data:r}=b.storage.from("blog").getPublicUrl(t.image_url);return r.publicUrl}return"https://images.unsplash.com/photo-1505238680356-6678f244482a?q=80&w=2070&auto-format&fit=crop"})(),x={"@context":"https://schema.org","@type":"BlogPosting",headline:t.title,image:[n],datePublished:t.created_at,dateModified:t.created_at,author:{"@type":"Person",name:t.author_name||"Resona Team"},publisher:{"@type":"Organization",name:"Resona",logo:{"@type":"ImageObject",url:"https://www.resona.app.br/logo.png"}},description:t.content.substring(0,160),articleBody:t.content.substring(0,5e3)};return e.jsxs("div",{className:"bg-dark text-white font-sans",children:[e.jsx(j,{title:t.title,description:t.content.substring(0,160),image:n,type:"article",schema:x}),e.jsxs("div",{className:"relative h-96",children:[e.jsx("img",{src:n,alt:t.title,className:"w-full h-full object-cover"}),e.jsx("div",{className:"absolute inset-0 bg-black/60 flex flex-col justify-end p-8 md:p-12",children:e.jsxs("div",{className:"container mx-auto px-4 sm:px-6 lg:px-8",children:[e.jsx("h1",{className:"text-3xl md:text-5xl font-extrabold text-white font-poppins",children:t.title}),e.jsxs("p",{className:"mt-2 text-gray-300",children:["Por ",t.author_name," • ",d]})]})})]}),e.jsx("div",{className:"container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16",children:e.jsx("article",{className:"prose prose-invert lg:prose-xl max-w-4xl mx-auto",children:t.content.split(`
`).map((r,s)=>e.jsx("p",{children:r},s))})}),e.jsx("style",{children:`
                .prose {
                    color: #d1d5db; /* text-gray-300 */
                }
                .prose p {
                    margin-bottom: 1.25em;
                    line-height: 1.7;
                }
                .prose h1, .prose h2, .prose h3 {
                    color: #ffffff;
                    font-family: 'Poppins', sans-serif;
                }
                .prose a {
                    color: #51D2EE; /* primary */
                    text-decoration: none;
                }
                .prose a:hover {
                    text-decoration: underline;
                }
                .prose strong {
                    color: #ffffff;
                }
                .prose-xl {
                    font-size: 1.25rem;
                }
            `})]})};export{w as default};
