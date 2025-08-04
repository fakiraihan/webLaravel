<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::create([
            'title' => 'The Future of AI in Software Development',
            'content' => 'Artificial intelligence (AI) is rapidly changing the landscape of software development, introducing new tools and methodologies that enhance efficiency and creativity. AI-powered tools can automate repetitive tasks, such as code generation and testing, allowing developers to focus on more complex problem-solving and innovative design. This shift not only accelerates development cycles but also reduces the likelihood of human error, leading to more robust and reliable software solutions. Furthermore, AI algorithms can analyze vast amounts of code to identify patterns and suggest optimizations, improving code quality and performance. As AI continues to evolve, its integration into software development workflows will become increasingly seamless, enabling developers to build more sophisticated applications with greater ease and speed. The future of software development is undoubtedly intertwined with AI, promising a new era of innovation and productivity.',
            'category' => 'Technology',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBrcA7kEXYBZ80r_sDsVBmEYAldd0Ah00_Lk5oIZZ0h5nyHIXpuLAD8GBRgCKhIK0YCtHyr7rO2Jhr3rdW7_GFBLWRPlERYKUI2rsMKj9CODaB8TakGoJx89WHLzeSJFhlKjBvmUFWNEvHO3jFK-CJZ15AmnSomj332Nz1OQRc3eTyXDNNO6MgM43RQwBvI0GnzQGx0PCF7d2dqBJdREo8SNyxh3Uo8xVxaQbWAdHkqEGIEAJE_yXZ9Re3JytBWPsMOlsUx7VuBVS8',
            'author_name' => 'Sophia Carter',
            'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCipXpga9SNDEogDGipY_9BH0xK1D7qozMM5thKpRLWrGqZUMbE7duAuIHYtUJyCsUqIyCZwmy2f8AQp_GW5_vv9N3YjsURNSN9pOfQE06hvDibOZ5hUt7wiE1F_4NVlGY06aozkONVQ3HGRmNOM8JgTMgmMqmmcZFwQggoEZBZOAzTYG2ICVnthV7p5m61YeeHpaiZa7qtSLp7E9ZbEr0-89W0M3g9NFuVo_js9BdtudbyWQZfIoNlFHI0WxZmKLHDs-1fhCKQBaw',
            'status' => 'published'
        ]);

        Article::create([
            'title' => 'Mastering Python: Advanced Techniques',
            'content' => 'Python has emerged as one of the most popular programming languages in the world, thanks to its simplicity, versatility, and powerful ecosystem. For developers looking to take their Python skills to the next level, mastering advanced techniques is essential. This includes understanding decorators, context managers, generators, and metaclasses. Decorators allow you to modify or extend the functionality of functions and classes without permanently altering them. Context managers provide a clean way to handle resource management, ensuring that resources are properly acquired and released. Generators offer memory-efficient iteration over large datasets, while metaclasses provide a way to control class creation. Additionally, mastering Python\'s built-in modules like itertools, functools, and collections can significantly improve code efficiency and readability. Understanding asynchronous programming with asyncio is also crucial for building high-performance applications. By incorporating these advanced techniques into your Python toolkit, you\'ll be able to write more efficient, maintainable, and elegant code.',
            'category' => 'Programming',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB43AxgtuGRzpnBaXuhwnwASnZs4GsXz_uffKIo20KBK2GErwC3MKJnlkNezo4RzywFp0LNCMO2xkjPdihTQc5VbffMnktat8tHLguQNtty_NAdpQ8gsXyHEO4xaPo6gpyl3n6Hd1bvp7CmiwCQN5UK3xAhggeMXbNvXM4mMhykwXdrHEY1RLJtnwI-2OyZfxSjtXqs2LzyFjknhUPNMbDhsnwahI_dEmAJg4ADHnwaVLexpVxm9O_rLr3agDB-REKLnv_koMKhcVI',
            'author_name' => 'John Smith',
            'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBkaqnHFvcp3l9Xte_-cLKVaxZimXcaX2BvhJGKdJRH3jCzeX2r-g1Lka8JjpmEJU62iyvwRjtNQG07SjN7Tpy1Ebpe1eooF9pvLLuwwezfqURNbl7ok4WQB0uAZ4YOs6VJxasQ1RyRr94mO2FE8S1B1EgsVdR-Ga5QGkSvFgXAINMmvuKp1qyp1DTCEemwukv7j2kdIQ86mrId3SOI1Ot-HiLgarjGkK3gELCdwedozhVVLwrYTKrFvtQ3NOOwXWcuT3Ylelxy-r8',
            'status' => 'published'
        ]);

        Article::create([
            'title' => 'Web Development Trends 2024',
            'content' => 'The web development landscape continues to evolve rapidly, with new trends and technologies emerging every year. In 2024, we\'re seeing significant shifts towards more performant, accessible, and user-friendly web applications. Progressive Web Apps (PWAs) are gaining traction as they provide native app-like experiences directly through web browsers. Server-side rendering (SSR) and static site generation (SSG) are becoming standard practices for improving performance and SEO. The rise of headless CMS solutions is enabling developers to create more flexible and scalable content management systems. WebAssembly (WASM) is opening up new possibilities for running high-performance code in browsers, while Web3 technologies are creating opportunities for decentralized applications. Additionally, there\'s an increased focus on accessibility, with developers prioritizing inclusive design principles to ensure their applications are usable by everyone. The adoption of modern CSS features like Grid and Flexbox has also streamlined responsive design workflows.',
            'category' => 'Web Development',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAAL85K6vy8mZRF1hhUHwOuiSmiEQmux1TFw59HYpOZdoNhxzabq232fGbT_JxfE_k86w2k--xyIHgyZxAcl7H8z9igXFx4GqvLnyFm5PsA2OnhqMG13atJ1W77piIZPiSltkIyKN-TvD2QPsQ2nV-XHHEPM_H7UxtHbsZ8VCW6S7u95AaiVtHdOjoH8Wnr6NYUh46Z53qwtAY8EtGWqopoiXz_HULJwlNQ-kHebn_-9794zzJWcxv-vgbkcP1nVaG9ScoB-v95v2w',
            'author_name' => 'Emily Johnson',
            'author_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBX-rN68T5s5ZpC3tiMbFzRTHrvP4RnHae-niHwHVhQrs2loL8F6EzyFigeY8645iMntQ3xP5kO5SKJ_zzZ_V5QBcCUgqYUvRjdQb4Yh2juXN23jcxxZdaBg-gPOmcLtuPQi4W0Jjc3uO_44GrL1LdWezmsghGTRAHdM_wfSNy9coOGna-1UQQVY7zsn52nMZHVoZFFpU_zLQo9UrjfhunfH0Wu5O2CmGuu8t-1Hbsw46cVNxclTwhz4jpBmMm-Ltf-2vg3pjc84MI',
            'status' => 'published'
        ]);
    }
}
