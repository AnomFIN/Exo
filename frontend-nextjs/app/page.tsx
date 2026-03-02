import Hero from '@/components/Hero'
import Services from '@/components/Services'
import MachineTradeSection from '@/components/MachineTradeSection'
import ProductCards from '@/components/ProductCards'
import JoniStory from '@/components/JoniStory'
import ContactSection from '@/components/ContactSection'
import Footer from '@/components/Footer'

export default function Home() {
  return (
    <main>
      <Hero />
      <Services />
      <MachineTradeSection />
      <ProductCards />
      <JoniStory />
      <ContactSection />
      <Footer />
    </main>
  )
}
