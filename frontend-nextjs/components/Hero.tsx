'use client'

import { useEffect, useState } from 'react'

export default function Hero() {
  const [visible, setVisible] = useState(false)

  useEffect(() => {
    setVisible(true)
  }, [])

  return (
    <section id="hero" className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background */}
      <div className="absolute inset-0 bg-gradient-to-br from-[#0a0a0a] via-[#111111] to-[#1a1a1a]" />
      
      {/* Construction site texture overlay */}
      <div className="absolute inset-0 opacity-5"
        style={{
          backgroundImage: `repeating-linear-gradient(
            45deg,
            #F5C518 0px,
            #F5C518 1px,
            transparent 1px,
            transparent 8px
          )`,
        }}
      />

      {/* Yellow accent bar */}
      <div className="absolute top-0 left-0 w-full h-1 bg-[#F5C518]" />

      {/* Side accent lines */}
      <div className="absolute left-0 top-1/4 bottom-1/4 w-1 bg-gradient-to-b from-transparent via-[#F5C518] to-transparent opacity-60" />

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-16">
        <div className={`transition-all duration-1000 ${visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
          <div className="inline-flex items-center gap-2 bg-[#F5C518]/10 border border-[#F5C518]/30 px-4 py-2 mb-8">
            <span className="w-2 h-2 rounded-full bg-[#F5C518] animate-pulse" />
            <span className="text-[#F5C518] text-sm font-semibold tracking-widest uppercase">Järvenpää, Suomi</span>
          </div>

          <h1 className="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-tight mb-6 tracking-tight">
            <span className="block text-white">Maansiirtoa.</span>
            <span className="block text-white">Konekauppaa.</span>
            <span className="block text-[#F5C518]">Vientiä ilman välikäsiä.</span>
          </h1>

          <p className="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-10 font-medium">
            Luotettava maansiirto ja suora konekauppa — <span className="text-gray-200">ammattilaisen varmuudella</span>
          </p>

          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <a
              href="#yhteystiedot"
              className="inline-flex items-center justify-center bg-[#F5C518] text-black px-8 py-4 text-base font-black uppercase tracking-widest hover:bg-yellow-400 transition-all duration-200 hover:scale-105 shadow-lg shadow-[#F5C518]/20"
            >
              Pyydä tarjous
              <svg className="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M9 5l7 7-7 7" />
              </svg>
            </a>
            <a
              href="#palvelut"
              className="inline-flex items-center justify-center border-2 border-gray-600 text-gray-300 px-8 py-4 text-base font-bold uppercase tracking-widest hover:border-[#F5C518] hover:text-[#F5C518] transition-all duration-200"
            >
              Katso palvelut
            </a>
          </div>
        </div>

        {/* Stats bar */}
        <div className={`mt-20 grid grid-cols-3 gap-px bg-[#2a2a2a] border border-[#2a2a2a] transition-all duration-1000 delay-300 ${visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
          {[
            { value: '2022', label: 'Perustettu' },
            { value: '430k€', label: 'Liikevaihto 2025' },
            { value: '100%', label: 'Tyytyväisyys' },
          ].map((stat) => (
            <div key={stat.label} className="bg-[#111111] px-4 py-6 text-center">
              <div className="text-2xl sm:text-3xl font-black text-[#F5C518]">{stat.value}</div>
              <div className="text-xs text-gray-500 uppercase tracking-widest mt-1">{stat.label}</div>
            </div>
          ))}
        </div>
      </div>

      {/* Scroll indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-gray-600">
        <span className="text-xs uppercase tracking-widest">Vieritä alas</span>
        <div className="w-px h-12 bg-gradient-to-b from-gray-600 to-transparent animate-pulse" />
      </div>
    </section>
  )
}
