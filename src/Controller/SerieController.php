<?php
namespace App\Controller;

use App\Entity\Serie; // Import the Serie entity
use App\Form\SerieType; // Import the SerieType form class
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface; // Import the EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class SerieController extends AbstractController
{
    #[Route('/series', name: 'series_list')]
    public function index(SerieRepository $serieRepository, Request $request): Response
    {
        $search = $request->query->get('search', '');
        $status = $request->query->get('status', '');
        $genre = $request->query->get('genre', ''); // Get the genre filter
        $sort = $request->query->get('sort', 'id');
        $direction = $request->query->get('direction', 'asc');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 12;
    
        $series = $serieRepository->findFilteredSeries($search, $status, $genre, $sort, $direction, $page, $limit);
        $totalSeries = $serieRepository->countFilteredSeries($search, $status, $genre);
    
        return $this->render('series/index.html.twig', [
            'series' => $series,
            'totalSeries' => $totalSeries,
            'currentPage' => $page,
            'limit' => $limit,
            'search' => $search,
            'status' => $status,
            'genre' => $genre, // Pass the genre to the template
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    #[Route('/series/{id}', name: 'series_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
    
        if (!$serie) {
            throw $this->createNotFoundException('The series does not exist.');
        }
    
        // Extract the correct ID from the poster filename
        $posterFilename = $serie->getPoster(); // e.g., "the-twilight-zone-6357.jpg"
        
        if (preg_match('/-(\d+)\.jpg$/', $posterFilename, $matches)) {
            $correctId = $matches[1];
        } else {
            // Handle invalid filenames gracefully
            $this->addFlash('error', 'Invalid poster filename format.');
            return $this->redirectToRoute('series_list'); // Redirect to the series list or another page
        }

        // Generate the series slug using the series name and the correct ID
        $seriesSlug = strtolower($serie->getName());
        $seriesSlug = preg_replace("/[^a-z0-9]+/i", '-', $seriesSlug); // Replace non-alphanumeric characters with hyphens
        $seriesSlug = trim($seriesSlug, '-') . '-' . $correctId;
    
        // Scan the directory for matching season posters
        $posterPath = $this->getParameter('kernel.project_dir') . '/public/images/posters/seasons/';
        $pattern = $posterPath . $seriesSlug . '_*.jpg';
        $seasonPosters = glob($pattern);
    
        // Debugging: Log the results of glob()
        dump($seriesSlug, $posterPath, $pattern, $seasonPosters);
    
        // Extract only the filenames to pass to the template
        $seasonPosters = array_map(function ($poster) use ($posterPath) {
            return str_replace($posterPath, '', $poster);
        }, $seasonPosters);
    
        return $this->render('series/detail.html.twig', [
            'serie' => $serie,
            'seasonPosters' => $seasonPosters,
        ]);
    }

    
    #[Route('/series/new', name: 'series_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $form = $this->createForm(SerieType::class, $serie);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the uploaded file
            $posterFile = $form->get('posterFile')->getData();
            if ($posterFile) {
                // Generate a filename based on the series name and TMDB ID
                $seriesSlug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $serie->getName()), '-'));
                $newFilename = $seriesSlug . '-' . ($serie->getTmdbId() ?: 'default') . '.' . $posterFile->guessExtension();
                try {
                    // Move the file to the target directory
                    $posterFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/images/posters/series',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the file.');
                    return $this->redirectToRoute('series_new');
                }
    
                // Set the poster property to the new filename
                $serie->setPoster($newFilename);
            }
    
            $serie->setDateCreated(new \DateTime()); // Set the creation date
            $entityManager->persist($serie);
            $entityManager->flush();
    
            $this->addFlash('success', 'Series created successfully!');
            return $this->redirectToRoute('series_list');
        }
    
        return $this->render('series/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/series/{id}/delete', name: 'series_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {
        $serie = $serieRepository->find($id);
    
        if (!$serie) {
            throw $this->createNotFoundException('The series does not exist.');
        }
    
        // Delete the associated poster file
        if ($serie->getPoster()) {
            $posterPath = $this->getParameter('kernel.project_dir') . '/public/images/posters/series/' . $serie->getPoster();
            if (file_exists($posterPath)) {
                unlink($posterPath); // Remove the file
            }
        }
    
        // Remove the series from the database
        $entityManager->remove($serie);
        $entityManager->flush();
    
        $this->addFlash('success', 'Series deleted successfully!');
        return $this->redirectToRoute('series_list');
    }
}
