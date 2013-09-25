# Monolog RecursiveProcessor

[![Build Status](https://travis-ci.org/havvg/monolog-recursive-processor.png?branch=master)](https://travis-ci.org/havvg/monolog-recursive-processor)

The `RecursiveProcessor` is a data conversion processor for Monolog contexts.

## Attaching an actual Processor

The processing of a specific context entry is up to any other processor.

The `RecursiveProcessor` dispatches the `LogEvents::PROCESS_ENTRY` event with the data entry to be processed.

A processor converting data should listen on this event and convert its data accordingly.
