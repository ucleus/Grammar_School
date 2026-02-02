import { ArrowRight } from "lucide-react";

export default function Home() {
  return (
    <div className="flex min-h-screen flex-col items-center justify-center p-8 bg-brand-light">
      {/* Background Shapes */}
      <div className="fixed inset-0 pointer-events-none overflow-hidden select-none">
        <div className="absolute top-[10%] left-[-5%] w-[200px] h-[200px] rounded-full bg-primary opacity-40 animate-pulse blur-3xl"></div>
        <div className="absolute top-[60%] right-[-3%] w-[150px] h-[150px] rounded-full bg-secondary opacity-40 animate-pulse blur-3xl"></div>
      </div>

      <main className="z-10 text-center space-y-8 max-w-2xl bg-white/80 backdrop-blur-sm p-12 rounded-3xl shadow-xl border border-white/50">
        <div className="flex justify-center mb-4">
          <div className="w-20 h-20 bg-gradient-to-br from-brand-blue to-brand-green rounded-full flex items-center justify-center text-4xl shadow-lg">
            🦉
          </div>
        </div>

        <h1 className="text-5xl font-extrabold font-display bg-clip-text text-transparent bg-gradient-to-r from-brand-purple to-brand-pink pb-2">
          Grammar School
        </h1>

        <p className="text-xl text-slate-600 font-medium">
          Master English with fun, interactive lessons customized for your dialect.
        </p>

        <div className="flex justify-center gap-4 pt-4">
          <a href="/setup" className="px-8 py-3 bg-gradient-to-r from-secondary to-brand-green text-white font-bold rounded-full shadow-lg transform transition hover:scale-105 hover:shadow-xl flex items-center gap-2">
            Start Learning <ArrowRight size={20} />
          </a>
        </div>
      </main>
    </div>
  );
}
