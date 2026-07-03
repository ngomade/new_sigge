import React, { useEffect, useRef, useState } from 'react'
import Stepper from '../components/Stepper'
import PersonalInfo from '../components/stepItem/PersonalInfo'
import AcademiqueInfo from '../components/stepItem/AcademiqueInfo'
import OtherInfo from '../components/stepItem/OtherInfo'
import ConcoursInfo from '../components/stepItem/ConcoursInfo'
import Confirmation from '../components/stepItem/Confirmation'
import { useDispatch, useSelector } from 'react-redux'
import Loading from '../components/stepModal/Loading'
import { getSessionConcours } from '../api/routes/concours'
import { setCurrentSession } from '../app/modules/candidate'

function Candidate() {
  const step = useSelector((state) => state.stepper.step);
  const [isLoad, setLoadingState] = useState(false)
  const dispatch = useDispatch()
  // Garde-fou : évite que l'effet ne se redéclenche à l'infini.
  // Avant : l'effet dépendait de `sessionConcours`, qui était lui-même
  // réécrit (nouvelle référence d'objet) à chaque appel réseau -> boucle
  // infinie de requêtes vers l'API (cause probable des bannissements d'IP).
  const hasFetched = useRef(false)

  useEffect(() => {
    if (hasFetched.current) return
    hasFetched.current = true

    async function fetchSessionConcours() {
      try {
        setLoadingState(true)
        const res = await getSessionConcours()
        if (res.status === 200) {
          const data = await res.json()
          const { created_at, updated_at, ...session } = data
          dispatch(setCurrentSession(session))
        }
      } catch (error) {
        // Erreur réseau ou API : on n'affiche pas de session, mais on ne boucle pas
      } finally {
        setLoadingState(false)
      }
    }

    fetchSessionConcours()
  }, [dispatch])

  const steppers = [
    <PersonalInfo setLoadingState={setLoadingState} />,
    <AcademiqueInfo setLoadingState={setLoadingState} />,
    <ConcoursInfo setLoadingState={setLoadingState} />,
    <OtherInfo setLoadingState={setLoadingState} />,
    <Confirmation setLoadingState={setLoadingState} />
  ]
  return (
    <div>
      <Stepper>
        {steppers[parseInt(step) - 1]}
      </Stepper>
      {isLoad && <Loading />}
    </div>
  )
}

export default Candidate