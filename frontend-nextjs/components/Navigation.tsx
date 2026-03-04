'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'

export default function Navigation() {
  const [isOpen, setIsOpen] = useState(false)
  const [scrolled, setScrolled] = useState(false)

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 20)
    }
    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  const navLinks = [
    { href: '#palvelut', label: 'Palvelut' },
    { href: '#konekauppa', label: 'Konekauppa' },
    { href: '#tuotteet', label: 'Tuotteet' },
    { href: '#tarina', label: 'Tarina' },
    { href: '#yhteystiedot', label: 'Yhteystiedot' },
  ]

  return (
    <nav className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${scrolled ? 'bg-[#0a0a0a]/95 backdrop-blur-sm shadow-lg shadow-black/50' : 'bg-transparent'}`}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <Link href="/" className="flex items-center">
            <span className="text-2xl font-black text-[#F5C518] tracking-wider">EXVATOR</span>
            <span className="text-xs text-gray-400 ml-2 mt-1 font-medium">OY</span>
          </Link>

          <div className="hidden md:flex items-center space-x-8">
            {navLinks.map((link) => (
              <a
                key={link.href}
                href={link.href}
                className="text-gray-300 hover:text-[#F5C518] transition-colors duration-200 text-sm font-medium tracking-wide uppercase"
              >
                {link.label}
              </a>
            ))}
            <a
              href="#yhteystiedot"
              className="bg-[#F5C518] text-black px-4 py-2 text-sm font-bold uppercase tracking-wide hover:bg-yellow-400 transition-colors duration-200"
            >
              Pyydä tarjous
            </a>
          </div>

          <button
            className="md:hidden text-gray-300 hover:text-white focus:outline-none"
            onClick={() => setIsOpen(!isOpen)}
            aria-label="Toggle menu"
          >
            <div className="w-6 h-5 flex flex-col justify-between">
              <span className={`block w-full h-0.5 bg-current transition-all duration-300 ${isOpen ? 'rotate-45 translate-y-2' : ''}`} />
              <span className={`block w-full h-0.5 bg-current transition-all duration-300 ${isOpen ? 'opacity-0' : ''}`} />
              <span className={`block w-full h-0.5 bg-current transition-all duration-300 ${isOpen ? '-rotate-45 -translate-y-2' : ''}`} />
            </div>
          </button>
        </div>

        {isOpen && (
          <div className="md:hidden bg-[#1a1a1a] border-t border-[#2a2a2a]">
            <div className="px-2 pt-2 pb-3 space-y-1">
              {navLinks.map((link) => (
                <a
                  key={link.href}
                  href={link.href}
                  className="block px-3 py-2 text-gray-300 hover:text-[#F5C518] font-medium uppercase tracking-wide text-sm"
                  onClick={() => setIsOpen(false)}
                >
                  {link.label}
                </a>
              ))}
              <a
                href="#yhteystiedot"
                className="block px-3 py-2 bg-[#F5C518] text-black font-bold uppercase tracking-wide text-sm text-center mt-2"
                onClick={() => setIsOpen(false)}
              >
                Pyydä tarjous
              </a>
            </div>
          </div>
        )}
      </div>
    </nav>
  )
}
