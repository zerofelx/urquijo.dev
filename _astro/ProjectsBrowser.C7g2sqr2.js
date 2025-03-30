import{_ as x,p as h,t as v}from"./_plugin-vue_export-helper.DtUv5qh4.js";import{c as o,a as e,b as g,n as b,F as d,r as m,t as c,w as f,o as r,d as p}from"./runtime-dom.esm-bundler.BacumzGT.js";const w={name:"ProjectsFilter",data(){return{activeFilters:[],selectedProject:null,technologies:v,projects:h}},computed:{filteredProjects(){return this.activeFilters.length===0?this.projects:this.projects.filter(a=>{const s=a.technologies.map(u=>u.name);return this.activeFilters.some(u=>s.includes(u))})}},methods:{toggleFilter(a){this.activeFilters.includes(a)?this.activeFilters=this.activeFilters.filter(s=>s!==a):this.activeFilters.push(a)},clearFilters(){this.activeFilters=[]},openProjectDetails(a){this.selectedProject=a,document.body.style.overflow="hidden"},closeProjectDetails(){this.selectedProject=null,document.body.style.overflow=""}},beforeUnmount(){document.body.style.overflow=""}},_={class:"container mx-auto px-4 py-8"},y={class:"mb-8"},j={class:"flex flex-wrap gap-2 justify-center mb-4"},k=["onClick"],P=["src","alt"],F={class:"font-mono text-white flex flex-wrap gap-5 justify-center"},z=["onClick"],C=["src","alt"],D={class:"p-5 hover:text-white"},B={class:"mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"},V={class:"mb-3 font-normal text-gray-700 dark:text-gray-300"},N={class:"mb-3 font-normal text-gray-700 dark:text-gray-400 flex justify-end absolute bottom-2 right-2"},T=["src","alt"],U={key:0,class:"text-center py-12 text-white"},q={class:"w-full md:w-2/5 p-8 overflow-y-auto bg-zinc-900 relative"},A={class:"mb-8 border-b border-zinc-800 pb-6"},E={class:"text-4xl font-bold"},I={class:"mb-8"},J={class:"text-zinc-300 text-lg leading-relaxed"},L={class:"mb-8"},M={class:"flex flex-wrap gap-3"},O=["src","alt"],S={class:"text-black"},G={class:"w-full md:w-3/5 bg-black relative"},H={class:"h-full overflow-y-auto"},K={key:0},Q=["src"],R={class:"absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-white text-sm"},W=["src","alt"],X={class:"absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-white text-sm"};function Y(a,s,u,Z,l,i){return r(),o("div",_,[s[11]||(s[11]=e("div",{class:"flex justify-between items-center mb-4 text-white"},[e("a",{href:"/",class:"text-2xl font-mono"},"Jesus Urquijo")],-1)),e("div",y,[e("div",j,[e("button",{onClick:s[0]||(s[0]=(...t)=>i.clearFilters&&i.clearFilters(...t)),class:b(["px-4 py-2 rounded-full text-sm font-medium transition-colors text-white",l.activeFilters.length===0?"bg-zinc-700":"bg-zinc-800 hover:bg-zinc-700"])}," All ",2),(r(!0),o(d,null,m(Object.values(l.technologies),t=>(r(),o("button",{key:t.name,onClick:n=>i.toggleFilter(t.name),class:b(["px-4 py-2 rounded-full text-sm font-medium transition-colors flex items-center gap-2 text-black",l.activeFilters.includes(t.name)?"bg-amber-50":"bg-white hover:bg-amber-50"])},[e("img",{src:t.img,alt:t.name,class:"w-4 h-4"},null,8,P),p(" "+c(t.name),1)],10,k))),128))])]),e("div",F,[(r(!0),o(d,null,m(i.filteredProjects,t=>(r(),o("div",{key:t.name,class:"pb-20 relative max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-zinc-800 hover:bg-zinc-900 dark:border-gray-700 font-mono cursor-pointer transition-transform duration-300 hover:scale-[1.02]",onClick:n=>i.openProjectDetails(t)},[e("a",{href:"#",class:"text-white",onClick:s[1]||(s[1]=f(()=>{},["prevent"]))},[e("img",{src:t.image,alt:t.name,class:"w-full rounded-t-lg"},null,8,C)]),e("div",D,[e("a",{href:"#",onClick:s[2]||(s[2]=f(()=>{},["prevent"]))},[e("h5",B,c(t.name),1)]),e("p",V,c(t.legend),1),e("p",N,[(r(!0),o(d,null,m(t.technologies,n=>(r(),o("img",{key:n.name,src:l.technologies[n].img,alt:n.name,class:"bg-white rounded-2xl mr-2 w-15 p-2"},null,8,T))),128))])])],8,z))),128))]),i.filteredProjects.length===0?(r(),o("div",U,[s[7]||(s[7]=e("p",{class:"text-xl"},"No projects match your filter criteria.",-1)),e("button",{onClick:s[3]||(s[3]=(...t)=>i.clearFilters&&i.clearFilters(...t)),class:"mt-4 text-zinc-400 font-medium hover:underline"},"Clear Filters")])):g("",!0),l.selectedProject?(r(),o("div",{key:1,class:"fixed inset-0 bg-black/90 z-50 flex items-center justify-center font-mono",onClick:s[6]||(s[6]=(...t)=>i.closeProjectDetails&&i.closeProjectDetails(...t))},[e("div",{class:"bg-zinc-900 text-white w-[95%] h-[90%] max-w-[1800px] rounded-xl overflow-hidden flex flex-col md:flex-row shadow-2xl",onClick:s[5]||(s[5]=f(()=>{},["stop"]))},[e("button",{onClick:s[4]||(s[4]=(...t)=>i.closeProjectDetails&&i.closeProjectDetails(...t)),class:"cursor-pointer absolute top-4 right-4 z-50 bg-zinc-800/80 hover:bg-zinc-700 text-white rounded-full w-10 h-10 flex items-center justify-center transition-colors"},s[8]||(s[8]=[e("span",{class:"text-2xl"},"×",-1)])),e("div",q,[e("div",A,[e("h2",E,c(l.selectedProject.name),1)]),e("div",I,[s[9]||(s[9]=e("h3",{class:"text-2xl font-semibold mb-4 text-zinc-200"},"Descripción",-1)),e("p",J,c(l.selectedProject.description),1)]),e("div",L,[s[10]||(s[10]=e("h3",{class:"text-2xl font-semibold mb-4 text-zinc-200"},"Tecnologías",-1)),e("div",M,[(r(!0),o(d,null,m(l.selectedProject.technologies,t=>(r(),o("div",{key:t,class:"flex items-center gap-2 bg-white px-4 py-2 rounded-lg"},[e("img",{src:l.technologies[t].img,alt:t,class:"w-8 h-8"},null,8,O),e("span",S,c(l.technologies[t].name),1)]))),128))])])]),e("div",G,[e("div",H,[l.selectedProject.videos&&l.selectedProject.videos.length>0?(r(),o("div",K,[(r(!0),o(d,null,m(l.selectedProject.videos,(t,n)=>(r(),o("div",{key:n,class:"relative mb-4"},[e("video",{src:t,controls:"",class:"w-full object-contain"},null,8,Q),e("div",R," Video "+c(n+1)+" de "+c(l.selectedProject.videos.length),1)]))),128))])):g("",!0),(r(!0),o(d,null,m(l.selectedProject.images,(t,n)=>(r(),o("div",{key:n,class:"relative"},[e("img",{src:t,alt:`${l.selectedProject.name} image ${n+1}`,class:"w-full object-contain"},null,8,W),e("div",X," Imagen "+c(n+1)+" de "+c(l.selectedProject.images.length),1)]))),128))])])])])):g("",!0)])}const te=x(w,[["render",Y]]);export{te as default};
