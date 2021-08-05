<?php

// src/Controller/LuckyController.php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    /**
     * @Route ("/", name="homepage")
     * @Route ("/lucky/number")
     *
     * Example method that gets a random integer and sleeps for some few milliseconds
     *
     * @throws \Exception
     */
    public function number(LoggerInterface $logger): Response
    {
        // Initial timestamp
        $startTimestamp = \microtime(true);
        $logger->debug(\sprintf('Job started at %.2f (%s).', $startTimestamp, \date('H:i:s', (int) $startTimestamp)));

        // Make the job :)
        $number = random_int(0, 100);
        sleep($number / 100);

        // Final timestamp
        $endTimestamp = \microtime(true);
        $logger->debug(\sprintf('Job ended at %.2f (%s).', $endTimestamp, \date('H:i:s', (int) $endTimestamp)));
        $duration = \round(($endTimestamp - $startTimestamp));

        $logger->info(\sprintf('Job completed. Started at %.2f (%s). Duration : %d s', $startTimestamp, \date('H:i:s', (int) $startTimestamp), $duration));

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}
